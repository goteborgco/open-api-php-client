<?php

namespace GBGCO\Types;

use GBGCO\Client;
use GBGCO\Types\Entities\TaxonomyTerm;
use GBGCO\Traits\GraphQLFieldsTrait;

/**
 * API client for querying specific taxonomies
 * 
 * This class provides methods for retrieving terms from specific taxonomies,
 * with support for filtering, field selection, and hierarchical organization.
 */
class Taxonomy
{
    use GraphQLFieldsTrait;

    private Client $client;

    /**
     * Initialize the taxonomy API client
     * 
     * @param Client $client The HTTP client for making API requests
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * List all terms in a specific taxonomy
     *
     * @param string $taxonomyName The name of the taxonomy to query (e.g. "categories")
     * @param array $filter Filter options:
     *                      - lang: string ('en'|'sv') - Language filter
     *                      - Other filters are passed directly to the API
     * @param array|string $fields Fields to retrieve, either as:
     *                            - A string in GraphQL format
     *                            - An array of field names and nested selections
     * @param bool $hierarchical Whether to return terms in a hierarchical tree structure
     * @return TaxonomyTerm[]|array When hierarchical is false:
     *                              - Returns a flat array of TaxonomyTerm objects
     *                              When hierarchical is true:
     *                              - Returns a nested array where each node has:
     *                                - 'term': TaxonomyTerm
     *                                - 'children': array of child nodes
     * @throws \InvalidArgumentException If:
     *                                  - The taxonomy name is empty
     *                                  - The language filter is invalid
     *                                  - The fields selection is empty
     * @throws \Exception If the API request fails
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
     * 
     * @param string $taxonomyName Name of the taxonomy to query
     * @param string $fields GraphQL fields selection string
     * @param string $filterStr Filter arguments string
     * @return string Complete GraphQL query
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
     * @return array Hierarchical array where each node has:
     *               - 'term': TaxonomyTerm - The term object
     *               - 'children': array - Array of child nodes with the same structure
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
     * @return array Array of nodes, where each node has:
     *               - 'term': TaxonomyTerm - The term object
     *               - 'children': array - Array of child nodes
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