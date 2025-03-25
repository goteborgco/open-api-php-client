<?php

namespace GBGCO\Types;

use GBGCO\Client;
use GBGCO\Types\Entities\WpEntity;
use GBGCO\Types\Entities\Markers;
use GBGCO\Types\Entities\Related;
use GBGCO\Traits\GraphQLFieldsTrait;

/**
 * API client for accessing event content
 * 
 * This class provides methods for retrieving events with support for filtering,
 * sorting, and field selection. Events can be retrieved individually or as lists,
 * with optional map marker data and related content.
 */
class Events
{
    use GraphQLFieldsTrait;

    private Client $client;

    /**
     * Initialize the events API client
     * 
     * @param Client $client The HTTP client for making API requests
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * List events with optional filtering, sorting and field selection
     *
     * @param array $filter Filter options:
     *                      - lang: string ('en'|'sv') - Language filter
     *                      - places: array<int> - Filter by place IDs
     *                      - categories: array<int> - Filter by category IDs
     *                      - areas: array<int> - Filter by area IDs
     *                      - tags: array<int> - Filter by tag IDs
     *                      - invisible_tags: array<int> - Exclude events with these tag IDs
     *                      - free: int - Filter by free admission (1 for free events)
     *                      - start: string - Start date (e.g., '2023-01-01')
     *                      - end: string - End date (e.g., '2023-12-31')
     *                      - distance: float - Distance in kilometers from coords
     *                      - coords: array [lat, lng] - Center point for distance filter
     *                      - per_page: int - Number of events per page
     *                      - page: int - Page number for pagination
     * @param array|null $sortBy Sorting options:
     *                          - fields: array<string> - Fields to sort by
     *                          - orders: array<'asc'|'desc'> - Sort direction for each field
     * @param array|string $fields Fields to retrieve, either as:
     *                            - A string in GraphQL format
     *                            - An array of field names and nested selections
     * @return object{events: WpEntity[], markers: ?Markers} Object containing:
     *                                                       - events: Array of event entities
     *                                                       - markers: Optional map markers data
     * @throws \InvalidArgumentException If the fields selection is empty
     * @throws \Exception If the API request fails
     */
    public function list(array $filter = [], ?array $sortBy = null, array|string $fields = []): object
    {
        $fieldsStr = $this->getFieldsString($fields);
        $filterStr = empty($filter) ? '' : $this->buildFilterString($filter);
        $sortStr = $sortBy ? $this->buildSortString($sortBy) : '';
        
        if (empty($filter) && empty($sortBy)) {
            $query = $this->buildListQuery($fieldsStr);
        } else {
            $query = $this->buildListQueryWithParams($fieldsStr, $filterStr, $sortStr);
        }

        $result = $this->client->execute($query);
        $data = $result['events'];

        return (object) [
            'events' => array_map(fn($event) => new WpEntity($event), $data['events'] ?? []),
            'markers' => isset($data['markers']) ? new Markers((array)$data['markers']) : null
        ];
    }

    /**
     * Get a specific event by ID
     *
     * @param int $id Event ID to retrieve
     * @param string $lang Language code ('en'|'sv')
     * @param array|string $fields Fields to retrieve, either as:
     *                            - A string in GraphQL format
     *                            - An array of field names and nested selections
     * @return object{event: WpEntity, markers: ?Markers, related: ?Related} Object containing:
     *                                                                       - event: The event entity
     *                                                                       - markers: Optional map markers data
     *                                                                       - related: Optional related content
     * @throws \InvalidArgumentException If the fields selection is empty
     * @throws \Exception If the API request fails
     */
    public function getById(int $id, string $lang = 'sv', array|string $fields = []): object
    {
        $fieldsStr = $this->getFieldsString($fields);
        $query = $this->buildByIdQuery($id, $lang, $fieldsStr);

        $result = $this->client->execute($query);
        $data = $result['eventById'];

        return (object) [
            'event' => new WpEntity($data['event'] ?? []),
            'markers' => isset($data['markers']) ? new Markers((array)$data['markers']) : null,
            'related' => isset($data['related']) ? new Related($data['related']) : null
        ];
    }

    /**
     * Build basic list query without parameters
     * 
     * @param string $fields GraphQL fields selection string
     * @return string Complete GraphQL query
     */
    private function buildListQuery(string $fields): string
    {
        return <<<GQL
        query {
            events {
                $fields
            }
        }
        GQL;
    }

    /**
     * Build list query with filter and sort parameters
     * 
     * @param string $fields GraphQL fields selection string
     * @param string $filterStr Filter arguments string
     * @param string $sortStr Sort arguments string
     * @return string Complete GraphQL query
     */
    private function buildListQueryWithParams(string $fields, string $filterStr, string $sortStr): string
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
            events($paramsStr) {
                $fields
            }
        }
        GQL;
    }

    /**
     * Build query for getting event by ID
     * 
     * @param int $id Event ID to retrieve
     * @param string $lang Language code
     * @param string $fields GraphQL fields selection string
     * @return string Complete GraphQL query
     */
    private function buildByIdQuery(int $id, string $lang, string $fields): string
    {
        return <<<GQL
        query {
            eventById(filter: { id: $id, lang: $lang }) {
                $fields
            }
        }
        GQL;
    }
}