<?php namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class PineconeSearch
{
    protected $client;
    protected $apiKey;
    protected $indexHost;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.pinecone.api_key');
        $this->indexHost = config('services.pinecone.index_host');
    }

    public function listVectors(string $namespace, string $prefix)
    {
        try {
            $response = $this->client->request('GET', $this->indexHost . "/vectors/list", [
                'headers' => [
                    'Api-Key' => $this->apiKey
                ],
                'query' => [
                    'namespace' => $namespace,
                    'prefix' => $prefix
                ]
            ]);

            return $response->getBody()->getContents();
        } catch (RequestException $e) {
            // Handle the exception as needed
            return 'HTTP request failed: ' . $e->getMessage();
        }
    }
}
