<?php

namespace GBGCO\Types;

use GBGCO\Client;
use GBGCO\Types\Entities\WpEntity;
use GBGCO\Traits\GraphQLFieldsTrait;

/**
 * API client for performing content searches
 * 
 * This class provides methods for searching across all content types with support
 * for filtering, sorting, and field selection. Search results include content from
 * places, events, guides, and other searchable content types.
 */
class Search
{
    use GraphQLFieldsTrait;

    private Client $client;

    /**
     * Initialize the search API client
     * 
     * @param Client $client The HTTP client for making API requests
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Search content with optional filtering, sorting and field selection
     *
     * @param array $filter Filter options:
     *                      - query: string (required) - The search query text
     *                      - lang: string ('en'|'sv') - Language filter
     * @param array|null $sortBy Sorting options:
     *                          - fields: array<string>|string - Fields to sort by
     *                          - orders: array<'asc'|'desc'>|'asc'|'desc' - Sort direction for each field
     * @param array|string $fields Fields to retrieve, either as:
     *                            - A string in GraphQL format
     *                            - An array of field names and nested selections
     * @return object{results: WpEntity[]} Object containing:
     *                                     - results: Array of matching content entities
     * @throws \InvalidArgumentException If:
     *                                  - The required query parameter is missing
     *                                  - The language filter is invalid
     *                                  - The fields selection is empty
     * @throws \Exception If the API request fails
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
     * 
     * @param string $fields GraphQL fields selection string
     * @param string $filterStr Filter arguments string
     * @param string $sortStr Sort arguments string
     * @return string Complete GraphQL query
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
                $fields
            }
        }
        GQL;
    }
} 