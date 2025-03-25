<?php

namespace GBGCO\Types;

use GBGCO\Client;
use GBGCO\Types\Entities\Taxonomy;
use GBGCO\Traits\GraphQLFieldsTrait;

/**
 * API client for listing available taxonomies
 * 
 * This class provides methods for retrieving information about all available
 * taxonomies in the system, such as categories, tags, and other classification types.
 */
class Taxonomies
{
    use GraphQLFieldsTrait;

    private Client $client;

    /**
     * Initialize the taxonomies API client
     * 
     * @param Client $client The HTTP client for making API requests
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * List all available taxonomies
     *
     * @param array $filter Filter options:
     *                      - lang: string ('en'|'sv') - Language filter
     *                      - Other filters are passed directly to the API
     * @param array|string $fields Fields to retrieve, either as:
     *                            - A string in GraphQL format
     *                            - An array of field names and nested selections
     * @return Taxonomy[] Array of Taxonomy objects, each representing a taxonomy type
     * @throws \InvalidArgumentException If:
     *                                  - The language filter is invalid
     *                                  - The fields selection is empty
     * @throws \Exception If the API request fails
     */
    public function list(array $filter = [], array|string $fields = []): array
    {
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
        $query = $this->buildListQuery($fieldsStr, $filterStr);

        $result = $this->client->execute($query);
        
        return array_map(
            fn($item) => new Taxonomy($item),
            $result['taxonomies'] ?? []
        );
    }

    /**
     * Build list query with filter
     * 
     * @param string $fields GraphQL fields selection string
     * @param string $filterStr Filter arguments string
     * @return string Complete GraphQL query
     */
    private function buildListQuery(string $fields, string $filterStr): string
    {
        $filter = $filterStr ? "(filter: { $filterStr })" : '';

        return <<<GQL
        query {
            taxonomies$filter {
                $fields
            }
        }
        GQL;
    }
} 