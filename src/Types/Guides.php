<?php

namespace GBGCO\Types;

use GBGCO\Client;

class Guides
{
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
     * @return array<object>
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

        return array_map(fn($guide) => json_decode(json_encode($guide)), $guides);
    }

    /**
     * Get a specific guide by ID
     *
     * @param int $id Guide ID
     * @param string $lang Language code (e.g., 'sv')
     * @param array|string $fields Fields to retrieve, either as an array or GraphQL fields string
     * @return object
     */
    public function getById(int $id, string $lang = 'sv', array|string $fields = []): object
    {
        $fieldsStr = $this->getFieldsString($fields);
        $query = $this->buildByIdQuery($id, $lang, $fieldsStr);

        $result = $this->client->execute($query);
        return json_decode(json_encode($result['guideById']));
    }

    /**
     * Get fields string, handling both array and string inputs
     * 
     * @param array|string $fields Fields to retrieve
     * @return string
     * @throws \InvalidArgumentException When empty fields are provided
     */
    private function getFieldsString(array|string $fields): string
    {
        if ((is_string($fields) && empty($fields)) || (is_array($fields) && empty($fields))) {
            throw new \InvalidArgumentException('Fields cannot be empty');
        }

        if (is_string($fields)) {
            return $fields;
        }

        return $this->buildFieldsString($fields);
    }

    /**
     * Build GraphQL fields string from array
     */
    private function buildFieldsString(array $fields, int $indent = 0): string
    {
        $result = [];
        $indentStr = str_repeat('    ', $indent);
        
        foreach ($fields as $key => $value) {
            if (is_array($value)) {
                $nestedFields = $this->buildFieldsString($value, $indent + 1);
                $result[] = "$indentStr$key {\n$nestedFields\n$indentStr}";
            } else {
                $result[] = "$indentStr$value";
            }
        }

        return implode("\n", $result);
    }

    /**
     * Build filter arguments string from array
     */
    private function buildFilterString(array $filter): string
    {
        $filterArgs = [];
        foreach ($filter as $key => $value) {
            if (is_string($value)) {
                $filterArgs[] = "$key: \"$value\"";
            } else {
                $filterArgs[] = "$key: $value";
            }
        }
        return implode(', ', $filterArgs);
    }

    /**
     * Build basic list query without filters
     */
    private function buildListQuery(string $fields): string
    {
        return <<<GQL
        query {
            guides {
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
            guides(filter: { $filterStr }) {
                $fields
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