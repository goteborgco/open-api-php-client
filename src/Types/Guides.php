<?php

namespace GBGCO\Types;

use GBGCO\Client;
use GBGCO\Types\Entities\WpEntity;
use GBGCO\Types\Entities\Related;
use GBGCO\Traits\GraphQLFieldsTrait;

class Guides
{
    use GraphQLFieldsTrait;

    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * List guides with specified filters and fields
     *
     * @param array $filter Filter parameters (lang, per_page, page)
     * @param array|string $fields Fields to retrieve, either as an array or GraphQL fields string
     * @return WpEntity[]
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
     * @param int $id Guide ID
     * @param string $lang Language code (e.g., 'sv')
     * @param array|string $fields Fields to retrieve, either as an array or GraphQL fields string
     * @return object{guide: WpEntity, related: Related}
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