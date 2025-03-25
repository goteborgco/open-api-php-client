<?php

namespace GBGCO\Types;

use GBGCO\Client;
use GBGCO\Types\Entities\WpEntity;
use GBGCO\Types\Entities\Related;
use GBGCO\Traits\GraphQLFieldsTrait;

/**
 * API client for accessing guide content
 * 
 * This class provides methods for retrieving guides with support for filtering
 * and field selection. Guides can be retrieved individually or as lists,
 * with optional related content.
 */
class Guides
{
    use GraphQLFieldsTrait;

    private Client $client;

    /**
     * Initialize the guides API client
     * 
     * @param Client $client The HTTP client for making API requests
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * List guides with specified filters and fields
     *
     * @param array $filter Filter options:
     *                      - lang: string ('en'|'sv') - Language filter
     *                      - categories: array<int> - Filter by category IDs
     *                      - areas: array<int> - Filter by area IDs
     *                      - tags: array<int> - Filter by tag IDs
     *                      - invisible_tags: array<int> - Exclude guides with these tag IDs
     *                      - per_page: int - Number of guides per page
     *                      - page: int - Page number for pagination
     * @param array|string $fields Fields to retrieve, either as:
     *                            - A string in GraphQL format
     *                            - An array of field names and nested selections
     * @return WpEntity[] Array of guide entities
     * @throws \InvalidArgumentException If the fields selection is empty
     * @throws \Exception If the API request fails
     */
    public function list(array $filter = [], array|string $fields = []): array
    {
        $fieldsStr = $this->getFieldsString($fields);
        
        if (empty($filter)) {
            $query = $this->buildListQuery($fieldsStr);
        } else {
            $query = $this->buildListQueryWithFilter($fieldsStr, $this->buildFilterString($filter));
        }

        $result = $this->client->execute($query);
        $guides = $result['guides']['guides'];

        return array_map(fn($guide) => new WpEntity($guide), $guides);
    }

    /**
     * Get a specific guide by ID
     *
     * @param int $id Guide ID to retrieve
     * @param string $lang Language code ('en'|'sv')
     * @param array|string $fields Fields to retrieve, either as:
     *                            - A string in GraphQL format
     *                            - An array of field names and nested selections
     * @return object{guide: WpEntity, related: Related} Object containing:
     *                                                   - guide: The guide entity
     *                                                   - related: Optional related content
     * @throws \InvalidArgumentException If the fields selection is empty
     * @throws \Exception If the API request fails
     */
    public function getById(int $id, string $lang = 'sv', array|string $fields = []): object
    {
        $fieldsStr = $this->getFieldsString($fields);
        $query = $this->buildByIdQuery($id, $lang, $fieldsStr);

        $result = $this->client->execute($query);
        $data = $result['guideById'];

        return (object) [
            'guide' => new WpEntity($data['guide'] ?? []),
            'related' => isset($data['related']) ? new Related($data['related']) : null
        ];
    }

    /**
     * Build basic list query without filters
     * 
     * @param string $fields GraphQL fields selection string
     * @return string Complete GraphQL query
     */
    private function buildListQuery(string $fields): string
    {
        return <<<GQL
        query {
            guides {
                guides {
                    $fields
                }
            }
        }
        GQL;
    }

    /**
     * Build list query with filters
     * 
     * @param string $fields GraphQL fields selection string
     * @param string $filterStr Filter arguments string
     * @return string Complete GraphQL query
     */
    private function buildListQueryWithFilter(string $fields, string $filterStr): string
    {
        return <<<GQL
        query {
            guides(filter: { $filterStr }) {
                guides {
                    $fields
                }
            }
        }
        GQL;
    }

    /**
     * Build query for getting guide by ID
     * 
     * @param int $id Guide ID to retrieve
     * @param string $lang Language code
     * @param string $fields GraphQL fields selection string
     * @return string Complete GraphQL query
     */
    private function buildByIdQuery(int $id, string $lang, string $fields): string
    {
        return <<<GQL
        query {
            guideById(filter: { id: $id, lang: $lang }) {
                $fields
            }
        }
        GQL;
    }
} 