<?php

namespace App\Livewire;

use Arr;
use Livewire\Component;
use App\Models\Message;
use App\Models\Document;
use Illuminate\Support\Str;
use App\Models\Conversation;
use Probots\Pinecone\Client as Pinecone;
use App\Services\GeneratorOpenAIService;

class ChatBot extends Component
{
    protected $conversation;
    protected Document $currentDocument;
    protected bool $newSubject = false;

    public $messages = [];
    public $prompt = '';
    public $question = '';
    public $documents = [];
    private GeneratorOpenAIService $openAiService;

    public function boot(GeneratorOpenAIService $openAIService)
    {
        $this->openAiService = $openAIService;

        if (session()->exists('crschat')) {
            $sessionId = session()->get('crschat');
        } else {
            $sessionId = $this->_startNewSession();
        }

        $this->conversation = Conversation::firstOrCreate([
            'session_id' => $sessionId
        ]);

        // see if we have any current documents to use for this query
        // if not - this is our first search on the subject
        if ($this->conversation->documents->isEmpty()) {
            $this->newSubject = true;
        } else {
            $this->documents = $this->conversation->documents->map(function ($document, $key) {
                $currentDocument = Document::find($document->document_id);
                if ($document->active) {
                    $this->currentDocument = $currentDocument;
                }
                return [
                    'doc_id' => $currentDocument->id,
                    'doc_title' => $currentDocument->title
                ];
            });
        }

        // map out all our current messages to an array
        $this->messages = $this->conversation->messages->map(function (Message $message) {
            return [
                'content' => $message->content,
                'role' => $message->role
            ];
        })->toArray();

        $this->dispatch('scroll-to-bottom');
    }

    public function submitPrompt(): void
    {
        if (Str::contains($this->prompt, [
            'new search',
            'new subject'
        ])) {
            $this->_startNewSession();
            $this->prompt = '';
            $this->messages[] = [
                'role' => 'bot',
                'content' => 'Ok. What can I help you find next?'
            ];
            $this->documents = [];
            $this->dispatch('scroll-to-bottom');
            return;
        }

        $this->question = $this->prompt;

        $this->_storeConversationChunk($this->prompt, 'user');

        $this->messages[] = [
            'role' => 'user',
            'content' => $this->prompt
        ];

        $this->prompt = '';

        $this->dispatch('scroll-to-bottom');
        $this->js('$wire.ask()');
    }

    public function ask(): void
    {
        if ($this->newSubject) {
            $question = $this->openAiService->embedData([$this->question]);

            $pinecone = new Pinecone(env('PINECONE_API_KEY'), env('PINECONE_INDEX_HOST'));

            $currentDocs = $pinecone->data()
                ->vectors()
                ->query(vector: $question[0]->embedding, namespace: 'crsbot', topK: 10)
                ->json();

            // let's reduce down to just the unique document ID's
            $documentChunks = Arr::map($currentDocs['matches'], function (array $docChunk, string $key) {
                return [
                    'report_id' => Arr::first(explode('_', $docChunk['id']))
                ];
            });

            $documentIds = array_unique_multidimensional($documentChunks);

            // now create the final array of ID's and document titles
            // there is definitely a better way to streamline this -
            // but for now we will just do it this clunky way.
            $this->documents = Arr::map($documentIds, function ($value, $key) {
                $document = Document::where('report_id', $value)->first();
                $this->_storeDocument($document->id);
                return [
                    'doc_id' => $document->id,
                    'report_id' => $value['report_id'],
                    'doc_title' => $document->title
                ];
            });

            $messageText = 'Here are a few reports I found that may help. Please choose the one you would like more information on.';

            $this->_storeConversationChunk($messageText, 'assistant');

            $this->messages[] = [
                'role' => 'assistant',
                'content' => $messageText
            ];

            $this->newSubject = false;

        } else {
            $response = json_decode($this->openAiService->generateResponse($this->messages, $this->_generateChunkJson()));

            $this->messages[] = [
                'role' => 'system',
                'content' => $response->answer
            ];

            $this->_storeConversationChunk($response->answer, 'assistant');
        }

        $this->dispatch('scroll-to-bottom');
    }

    public function selectDocument($id): void
    {
        $document = Document::find($id);

        $messageText = 'Ok. How can I help you with this report?';

        $this->_storeConversationChunk($document->title, 'user');
        $this->_storeConversationChunk($messageText, 'assistant');

        $this->messages[] = [
            'role' => 'user',
            'content' => $document->title
        ];

        $this->messages[] = [
            'role' => 'assistant',
            'content' => $messageText
        ];

        $conversationDoc = $this->conversation->documents()->where('document_id', $document->id)->first();

        $conversationDoc->active = true;
        $conversationDoc->save();

        $this->dispatch('scroll-to-bottom');
    }

    private function _storeConversationChunk($message, $role): void {
        $this->conversation->messages()->create([
            'content' => $message,
            'role' => $role
        ]);
    }

    private function _storeDocument($documentId): void {
        $this->conversation->documents()->create([
            'conversation_id' => $this->conversation->id,
            'document_id' => $documentId,
        ]);
    }

    private function _generateChunkJson() {
        return $this->currentDocument->chunks->map(function ($chunk) {
            return [
                'text' => $chunk->text,
                'page_number' => $chunk->page_number,
            ];
        })->toJson();
    }

    private function _startNewSession(): string
    {
        session()->forget('crschat');
        session()->save();

        $sessionId = \Str::random(10);
        session()->put('crschat', $sessionId);

        return $sessionId;
    }
}