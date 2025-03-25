<?php

namespace GBGCO;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

class Client
{
    private string $apiUrl;
    private string $subscriptionKey;
    private GuzzleClient $httpClient;

    public function __construct(string $apiUrl, string $subscriptionKey)
    {
        $this->apiUrl = $apiUrl;
        $this->subscriptionKey = $subscriptionKey;
        $this->httpClient = new GuzzleClient([
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]
        ]);
    }

    /**
     * Execute a GraphQL query
     *
     * @param string $query The GraphQL query
     * @return array The query results
     * @throws \Exception
     */
    public function execute(string $query): array
    {
        try {
            $url = $this->apiUrl . '?subscription-key=' . urlencode($this->subscriptionKey);
            
            $payload = json_encode(['query' => $query]);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Failed to encode GraphQL payload: ' . json_last_error_msg());
            }

            $response = $this->httpClient->post($url, [
                RequestOptions::BODY => $payload
            ]);

            $statusCode = $response->getStatusCode();
            $body = (string)$response->getBody();

            if ($statusCode !== 200) {
                $errorDetail = json_decode($body, true);
                $errorMessage = isset($errorDetail['errors']) 
                    ? json_encode($errorDetail['errors'])
                    : $body;
                    
                throw new \Exception(
                    "HTTP request failed with status code: $statusCode\n" .
                    "Response: $errorMessage\n" .
                    "Query: $query"
                );
            }

            $result = json_decode($body, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Failed to decode API response: ' . json_last_error_msg());
            }

            if (isset($result['errors'])) {
                throw new \Exception(
                    "GraphQL errors: " . json_encode($result['errors']) . "\n" .
                    "Query: $query"
                );
            }

            return $result['data'] ?? [];
        } catch (GuzzleException $e) {
            throw new \Exception('GraphQL request failed: ' . $e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception('GraphQL query failed: ' . $e->getMessage());
        }
    }
} 