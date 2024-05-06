<?php

namespace App\Services;

use OpenAI;

class GeneratorOpenAIService
{
    private $client;

    public function __construct()
    {
        $this->client = OpenAI::client(config('services.openapi.key'));
    }

    public function generateResponse($questions, $data)
    {
        $systemPrompt = array();

        $systemPrompt[] = [
            'role' => 'system',
            'content' => sprintf(
                "You are a helpful data assistant working for a staffer in a Congressional office doing research using a report offered by the Congressional Research Service. You have access to this report in JSON format, each with a 'text' and 'page_number' node.\n\n---\n\n%s\n\n---\n\nBe cordial if they make a nice statement back to you. Always return the response in the following JSON format: { 'answer': '...detailed information...' }.",
                $data
            )
        ];


        foreach ($questions as $question) {
            $systemPrompt[] = [
                'role' => $question['role'],
                'content' => $question['content'],
            ];
        }

        $response = $this->client->chat()->create([
            'model' => 'gpt-4-turbo',
            'messages' => $systemPrompt,
            'response_format' => ['type' => 'json_object'],
            'temperature' => 0.7,
            'max_tokens' => 4096,
            'top_p' => 1,
            'frequency_penalty' => 0,
            'presence_penalty' => 0
        ]);

        return $response['choices'][0]['message']['content'];
    }

    public function embedData($data)
    {
        return $this->client->embeddings()->create([
            'model' => 'text-embedding-ada-002',
            'input' => $data
        ])->embeddings;
    }
}
