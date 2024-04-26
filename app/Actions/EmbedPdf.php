<?php namespace App\Actions;

use App\Models\Document;
use App\Services\GeneratorOpenAIService;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Str;
use League\Csv\Reader;
use Lorisleiva\Actions\Concerns\AsAction;
use \Probots\Pinecone\Client as Pinecone;
use Spatie\PdfToText\Pdf;

class EmbedPdf
{
    use AsAction;

    private $openAiService;
    private string $incompleteSentence;

    public function __construct(GeneratorOpenAIService $openaiService)
    {
        $this->openAiService = $openaiService;
        $this->incompleteSentence = '';
    }

    public $commandSignature = 'embed:pdf';

    public function handle()
    {
        $csvContents = Http::get('https://www.everycrsreport.com/reports.csv')->body();
        Storage::disk("local")->put('reports.csv', $csvContents);

        $reports = Reader::createFromPath(storage_path('app/reports.csv'));
        $reports->setHeaderOffset(0);
        $reports->getRecords();
        $reportCollection = LazyCollection::make(static fn () => yield from $reports);

        $reportCollection->take(500)->each(function ($report) {
            $json = Http::get('https://www.everycrsreport.com/'. $report['url'])->json();
            $url = $json['versions'][0]['formats'][0]['filename'];
            $pdf = Http::get('https://www.everycrsreport.com/'. $url);

            if ($pdf->forbidden()) {
                dd('can not get to file for id of ' . $json['id']);
            }

            Storage::disk('local')->put('current.pdf', $pdf->body());

            $fileText = Pdf::getText(
                storage_path('app/current.pdf'),
                config('services.pdftotext.path')
            );

            $pageContent = Str::of($fileText)
                ->split("/\f/")
                ->toArray();

//        dd($content[3]);
            $sanitizedPages = collect($pageContent)->map(function ($page) use ($report) {
                return $this->processText($page, $report['title']);
            })->toArray();

            $embeddings = $this->openAiService->embedData(Arr::pluck($sanitizedPages, 'text'));

            $pinecone = new Pinecone(env('PINECONE_API_KEY'), env('PINECONE_INDEX_HOST'));

//            $pinecone->data()->vectors()->delete(namespace: 'crsbot', deleteAll: true);
//            dd('deleted');

            collect($embeddings)->chunk(20)->each(function (Collection $chunk, $chunkIndex) use ($pinecone, $sanitizedPages, $report, $json) {
                $pinecone->data()->vectors()->upsert(
                    vectors: $chunk->pluck('embedding')->map(function ($embedding, $index) use ($report, $chunkIndex, $sanitizedPages, $json) {
                        $this->_storeDocumentChunkToSql([
                            'report_id' => $report['number'],
                            'chunk_id' => ($chunkIndex * 20 + $index),
                            'title' => $json['versions'][0]['title'],
                            'url' => 'https://www.everycrsreport.com/' . $json['versions'][0]['formats'][0]['filename'],
                            'text' => $sanitizedPages[$chunkIndex * 20 + $index]['text'],
                            'page_number' => $sanitizedPages[$chunkIndex * 20 + $index]['page_number']
                        ]);
                        return [
                            'id' => $report['number'] . '_' . ($chunkIndex * 20 + $index),
                            'values' => $embedding,
                        ];
                    })->toArray(),
                    namespace: 'crsbot'
                );
            });

            Storage::disk('local')->delete('current.pdf');
        });
    }

    public function asCommand(Command $command): void
    {
        $this->handle();
    }

    private function removeFootnotesSection($text): string
    {
        // Attempt to find the start of the footnotes section by looking for a number followed by text,
        // which should be at the end of the text or followed by a very distinct separator (like a double newline).
        $pattern = '/\n(\d+ [^\n]+)(\n\n|$)/';

        while (preg_match($pattern, $text, $matches, PREG_OFFSET_CAPTURE)) {
            $start = $matches[1][1];
            $text = substr($text, 0, $start);
        }

        return $text;
    }

    private function removeFootnoteNumbers($text): array|string|null
    {
        // Regular expression to match footnote numbers that appear at the end of sentences or paragraphs
        // This pattern looks for a space or start of line, followed by one or more digits, a period, and a space or end of line
        $pattern = '/(?<=\w)\.(\d+)(?=\n|\s|$)/';

        $cleanText = preg_replace($pattern, '', $text);
        return preg_replace($pattern, '', $text);
    }

    private function processText($text, $headerTitle): array
    {
        $pattern = "/\s*Congressional Research Service\s*\n+\s*(\d+)\s*$/";

        if (preg_match($pattern, $text, $matches)) {
            $pageNumber = $matches[1];
        } else {
            $pageNumber = 0;
        }

        $text = $this->removeFootnotesSection($text);
        $text = $this->removeFootnoteNumbers($text);
        $text = str_replace($headerTitle, '', $text);

        $cleanText = preg_replace('/\s+/', ' ', $text);
        $cleanText = $this->incompleteSentence . $cleanText;
        $this->incompleteSentence = '';

        $cleanText = $this->checkIncompleteSentence($cleanText);

        return [
            'text' => trim($cleanText),
            'page_number' => $pageNumber
        ];
    }

    private function checkIncompleteSentence($text): string {
        $lastPeriodPos = strrpos($text, '.');
        $isSentenceComplete = $lastPeriodPos === false || $lastPeriodPos === strlen($text) - 1;

        if (!$isSentenceComplete) {
            $lastCompleteSentenceEnd = strrpos($text, '.', -strlen($text) + $lastPeriodPos) + 1;
            $this->incompleteSentence = substr($text, $lastCompleteSentenceEnd);
            $text = substr($text, 0, $lastCompleteSentenceEnd);
        }

        return $text;
    }

    private function _storeDocumentChunkToSql($chunkedDoc)
    {
        if (!$currentDoc = Document::firstWhere('report_id', $chunkedDoc['report_id'])) {
            $currentDoc = Document::create(Arr::only($chunkedDoc, [
                'report_id',
                'title',
                'url'
            ]));
        }
        return $currentDoc->chunks()->create(Arr::only($chunkedDoc, [
            'chunk_id',
            'text',
            'page_number'
        ]));
    }
}
