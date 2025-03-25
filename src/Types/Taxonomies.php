<?php

namespace GBGCO\Types;

use GBGCO\Client;
use GBGCO\Types\Entities\Taxonomy;
use GBGCO\Traits\GraphQLFieldsTrait;

class Taxonomies
{
    use GraphQLFieldsTrait;

    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * List all taxonomies
     *
     * @param array $filter Filter options:
     *                      - lang: string ('en'|'sv')
     * @param array|string $fields Fields to retrieve, either as an array or GraphQL fields string
     * @return Taxonomy[] Returns an array of Taxonomy objects
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