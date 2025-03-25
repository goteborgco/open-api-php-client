<?php

namespace GBGCO\Types;

use GBGCO\Client;
use GBGCO\Types\Entities\TaxonomyTerm;
use GBGCO\Traits\GraphQLFieldsTrait;

class Taxonomy
{
    use GraphQLFieldsTrait;

    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * List all terms in a specific taxonomy
     *
     * @param string $taxonomyName The name of the taxonomy to query (e.g. "categories")
     * @param array $filter Filter options:
     *                      - lang: string ('en'|'sv')
     * @param array|string $fields Fields to retrieve, either as an array or GraphQL fields string
     * @param bool $hierarchical Whether to return terms in a hierarchical tree structure
     * @return TaxonomyTerm[]|array Returns an array of TaxonomyTerm objects. If hierarchical is true,
     *                              returns a nested array where each term has a 'children' key containing its child terms
     * @throws \InvalidArgumentException If the language is invalid or taxonomyName is empty
     */
    public function list(
        string $taxonomyName,
        array $filter = [],
        array|string $fields = [],
        bool $hierarchical = false
    ): array {
        if (empty($taxonomyName)) {
            throw new \InvalidArgumentException('Taxonomy name cannot be empty');
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
        $query = $this->buildListQuery($taxonomyName, $fieldsStr, $filterStr);

        $result = $this->client->execute($query);
        
        $terms = array_map(
            fn($item) => new TaxonomyTerm($item),
            $result['taxonomy'] ?? []
        );

        return $hierarchical ? $this->buildHierarchy($terms) : $terms;
    }

    /**
     * Build list query with filter and taxonomy name
     */
    private function buildListQuery(string $taxonomyName, string $fields, string $filterStr): string
    {
        $filter = $filterStr ? "filter: { $filterStr }, " : '';

        return <<<GQL
        query {
            taxonomy($filter taxonomyName: "$taxonomyName") {
                $fields
            }
        }
        GQL;
    }

    /**
     * Build a hierarchical tree structure from flat terms array
     * 
     * @param TaxonomyTerm[] $terms Flat array of taxonomy terms
     * @return array Hierarchical array where each term has a 'term' and 'children' key
     */
    private function buildHierarchy(array $terms): array
    {
        $termsByParent = [];
        
        // First, group terms by their parent ID
        foreach ($terms as $term) {
            $parentId = $term->getParent() ?? 0;
            if (!isset($termsByParent[$parentId])) {
                $termsByParent[$parentId] = [];
            }
            $termsByParent[$parentId][] = $term;
        }

        // Build the tree starting with root level terms (parent = 0 or null)
        return $this->buildTree($termsByParent, 0);
    }

    /**
     * Recursively build tree structure
     * 
     * @param array $termsByParent Terms grouped by parent ID
     * @param int $parentId Current parent ID to process
     * @return array Tree structure
     */
    private function buildTree(array $termsByParent, int $parentId): array
    {
        $tree = [];

        if (!isset($termsByParent[$parentId])) {
            return $tree;
        }

        foreach ($termsByParent[$parentId] as $term) {
            $node = [
                'term' => $term,
                'children' => $this->buildTree($termsByParent, $term->getId())
            ];
            $tree[] = $node;
        }

        return $tree;
    }
} 