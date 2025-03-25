<?php

namespace GBGCO\Types;

use GBGCO\Client;
use GBGCO\Types\Entities\WpEntity;
use GBGCO\Types\Entities\Markers;
use GBGCO\Types\Entities\Related;
use GBGCO\Traits\GraphQLFieldsTrait;

class Places
{
    use GraphQLFieldsTrait;

    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * List places with optional filtering and field selection
     *
     * @param array $filter Filter options:
     *                      - lang: string
     *                      - places: array
     *                      - categories: array
     *                      - areas: array
     *                      - tags: array
     *                      - distance: int
     *                      - coords: array [lat, lng]
     *                      - per_page: int
     *                      - page: int
     * @param array|string $fields Fields to retrieve, either as an array or GraphQL fields string
     * @return object{items: WpEntity[], markers: ?Markers} Returns an object containing WpEntity objects for places and a Markers object for map data
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
     * @param int $id Place ID
     * @param string $lang Language code (e.g., 'sv')
     * @param array|string $fields Fields to retrieve, either as an array or GraphQL fields string
     * @return object{place: WpEntity, events: WpEntity[], markers: ?Markers, related: ?Related}
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