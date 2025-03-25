<?php

namespace GBGCO\Types;

use GBGCO\Client;
use GBGCO\Types\Entities\WpEntity;
use GBGCO\Traits\GraphQLFieldsTrait;

class Search
{
    use GraphQLFieldsTrait;

    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Search content with optional filtering, sorting and field selection
     *
     * @param array $filter Filter options:
     *                      - query: string (required)
     *                      - lang: string ('en'|'sv')
     * @param array|null $sortBy Sorting options:
     *                          - fields: array<string>|string
     *                          - orders: array<'asc'|'desc'>|'asc'|'desc'
     * @param array|string $fields Fields to retrieve, either as an array or GraphQL fields string
     * @return object{results: WpEntity[]} Returns an object containing search results as WpEntity objects
     * @throws \InvalidArgumentException If required query parameter is missing
     */
    public function query(array $filter, ?array $sortBy = null, array|string $fields = []): object
    {
        if (empty($filter['query'])) {
            throw new \InvalidArgumentException('Search query cannot be empty');
        }

        if (isset($filter['lang']) && !in_array($filter['lang'], ['en', 'sv'])) {
            throw new \InvalidArgumentException('Language must be either "en" or "sv"');
        }

        // Remove quotes from lang value since it's an enum
        if (isset($filter['lang'])) {
            $filter['lang'] = strtolower($filter['lang']);
            $langValue = $filter['lang'];
            unset($filter['lang']);
            $filterStr = $this->buildFilterString($filter);
            $filterStr = $filterStr ? "$filterStr, lang: $langValue" : "lang: $langValue";
        } else {
            $filterStr = $this->buildFilterString($filter);
        }

        $fieldsStr = $this->getFieldsString($fields);
        $sortStr = $sortBy ? $this->buildSortString($sortBy) : '';
        
        $query = $this->buildSearchQuery($fieldsStr, $filterStr, $sortStr);
        $result = $this->client->execute($query);
        
        return (object) [
            'results' => array_map(fn($item) => new WpEntity($item), $result['search']['results'] ?? [])
        ];
    }

    /**
     * Build search query with parameters
     */
    private function buildSearchQuery(string $fields, string $filterStr, string $sortStr): string
    {
        $params = [];
        if ($filterStr) {
            $params[] = "filter: { $filterStr }";
        }
        if ($sortStr) {
            $params[] = $sortStr;
        }
        $paramsStr = implode(', ', $params);

        return <<<GQL
        query {
            search($paramsStr) {
                results {
                    $fields
                }
            }
        }
        GQL;
    }
} 