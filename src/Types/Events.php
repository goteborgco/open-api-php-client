<?php

namespace GBGCO\Types;

use GBGCO\Client;
use GBGCO\Types\Entities\WpEntity;
use GBGCO\Types\Entities\Markers;
use GBGCO\Types\Entities\Related;
use GBGCO\Traits\GraphQLFieldsTrait;

class Events
{
    use GraphQLFieldsTrait;

    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * List events with optional filtering, sorting and field selection
     *
     * @param array $filter Filter options:
     *                      - lang: string
     *                      - places: array<int>
     *                      - categories: array<int>
     *                      - areas: array<int>
     *                      - tags: array<int>
     *                      - invisible_tags: array<int>
     *                      - free: int
     *                      - start: string (start date, e.g., '2023-01-01')
     *                      - end: string (end date, e.g., '2023-12-31')
     *                      - distance: float (distance in kilometers from coords)
     *                      - coords: array [lat, lng]
     *                      - per_page: int
     *                      - page: int
     * @param array|null $sortBy Sorting options:
     *                          - fields: array<string>
     *                          - orders: array<'asc'|'desc'>
     * @param array|string $fields Fields to retrieve, either as an array or GraphQL fields string
     * @return object{events: WpEntity[], markers: ?Markers} Returns an object containing WpEntity objects for events and a Markers object for map data
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
     * @param int $id Event ID
     * @param string $lang Language code ('en'|'sv')
     * @param array|string $fields Fields to retrieve, either as an array or GraphQL fields string
     * @return object{event: WpEntity, markers: ?Markers, related: ?Related}
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
     * Build sort arguments string from array
     */
    private function buildSortString(array $sort): string
    {
        if (empty($sort['fields']) || empty($sort['orders'])) {
            return '';
        }

        $fields = is_array($sort['fields']) ? $sort['fields'] : [$sort['fields']];
        $orders = is_array($sort['orders']) ? $sort['orders'] : [$sort['orders']];

        return sprintf(
            'sortBy: { fields: [%s], orders: [%s] }',
            implode(', ', array_map(fn($field) => "\"$field\"", $fields)),
            implode(', ', $orders)
        );
    }

    /**
     * Build basic list query without parameters
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