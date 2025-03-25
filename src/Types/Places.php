<?php

namespace GBGCO\Types;

use GBGCO\Client;
use GBGCO\Types\Entities\WpEntity;
use GBGCO\Types\Entities\Markers;
use GBGCO\Types\Entities\Related;
use GBGCO\Traits\GraphQLFieldsTrait;

/**
 * API client for accessing place content
 * 
 * This class provides methods for retrieving places with support for filtering
 * and field selection. Places can be retrieved individually or as lists,
 * with optional map marker data, related content, and associated events.
 */
class Places
{
    use GraphQLFieldsTrait;

    private Client $client;

    /**
     * Initialize the places API client
     * 
     * @param Client $client The HTTP client for making API requests
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * List places with optional filtering and field selection
     *
     * @param array $filter Filter options:
     *                      - lang: string ('en'|'sv') - Language filter
     *                      - places: array<int> - Filter by place IDs
     *                      - categories: array<int> - Filter by category IDs
     *                      - areas: array<int> - Filter by area IDs
     *                      - tags: array<int> - Filter by tag IDs
     *                      - distance: int - Distance in kilometers from coords
     *                      - coords: array [lat, lng] - Center point for distance filter
     *                      - per_page: int - Number of places per page
     *                      - page: int - Page number for pagination
     * @param array|string $fields Fields to retrieve, either as:
     *                            - A string in GraphQL format
     *                            - An array of field names and nested selections
     * @return object{items: WpEntity[], markers: ?Markers} Object containing:
     *                                                      - items: Array of place entities
     *                                                      - markers: Optional map markers data
     * @throws \InvalidArgumentException If the fields selection is empty
     * @throws \Exception If the API request fails
     */
    public function list(array $filter = [], array|string $fields = []): object
    {
        $fieldsStr = $this->getFieldsString($fields);
        
        if (empty($filter)) {
            $query = $this->buildListQuery($fieldsStr);
        } else {
            $query = $this->buildListQueryWithFilter($fieldsStr, $this->buildFilterString($filter));
        }

        $result = $this->client->execute($query);
        $data = $result['places'];

        return (object) [
            'items' => array_map(fn($place) => new WpEntity($place), $data['places'] ?? []),
            'markers' => isset($data['markers']) ? new Markers((array)$data['markers']) : null
        ];
    }

    /**
     * Get a specific place by ID
     *
     * @param int $id Place ID to retrieve
     * @param string $lang Language code ('en'|'sv')
     * @param array|string $fields Fields to retrieve, either as:
     *                            - A string in GraphQL format
     *                            - An array of field names and nested selections
     * @return object{place: WpEntity, events: WpEntity[], markers: ?Markers, related: ?Related} Object containing:
     *                                                                                          - place: The place entity
     *                                                                                          - events: Array of associated events
     *                                                                                          - markers: Optional map markers data
     *                                                                                          - related: Optional related content
     * @throws \InvalidArgumentException If the fields selection is empty
     * @throws \Exception If the API request fails
     */
    public function getById(int $id, string $lang = 'sv', array|string $fields = []): object
    {
        $fieldsStr = $this->getFieldsString($fields);
        $query = $this->buildByIdQuery($id, $lang, $fieldsStr);

        $result = $this->client->execute($query);
        $data = $result['placeById'];

        return (object) [
            'place' => new WpEntity($data['place'] ?? []),
            'events' => array_map(fn($event) => new WpEntity($event), $data['events'] ?? []),
            'markers' => isset($data['markers']) ? new Markers((array)$data['markers']) : null,
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
            places {
                $fields
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
            places(filter: { $filterStr }) {
                $fields
            }
        }
        GQL;
    }

    /**
     * Build query for getting place by ID
     * 
     * @param int $id Place ID to retrieve
     * @param string $lang Language code
     * @param string $fields GraphQL fields selection string
     * @return string Complete GraphQL query
     */
    private function buildByIdQuery(int $id, string $lang, string $fields): string
    {
        return <<<GQL
        query {
            placeById(filter: { id: $id, lang: $lang }) {
                $fields
            }
        }
        GQL;
    }
}