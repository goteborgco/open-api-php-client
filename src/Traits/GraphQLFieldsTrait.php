<?php

namespace GBGCO\Traits;

/**
 * Trait for handling GraphQL field selection and query building
 * 
 * This trait provides methods for converting field selections between array and string formats,
 * and for building filter and sort arguments in GraphQL queries.
 */
trait GraphQLFieldsTrait
{
    /**
     * Get fields string, handling both array and string inputs
     * 
     * @param array|string $fields Fields to retrieve, either as:
     *                            - A string in GraphQL format
     *                            - An array of field names and nested selections
     * @return string The fields formatted as a GraphQL selection string
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
     * 
     * @param array $fields Nested array of field selections
     * @param int $indent Current indentation level for pretty printing
     * @return string The fields formatted as a GraphQL selection string
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
     * 
     * @param array $filter Associative array of filter criteria where:
     *                     - String values are quoted
     *                     - Array values become lists
     *                     - 'coords' arrays are converted to float lists
     *                     - Other arrays are converted to integer lists
     *                     - All other values are used as-is
     * @return string The filter formatted as GraphQL arguments
     */
    private function buildFilterString(array $filter): string
    {
        $filterArgs = [];
        foreach ($filter as $key => $value) {
            if (is_string($value)) {
                $filterArgs[] = "$key: \"$value\"";
            } elseif (is_array($value)) {
                if ($key === 'coords') {
                    $filterArgs[] = "$key: [" . implode(', ', array_map('floatval', $value)) . "]";
                } else {
                    $filterArgs[] = "$key: [" . implode(', ', array_map('intval', $value)) . "]";
                }
            } else {
                $filterArgs[] = "$key: $value";
            }
        }
        return implode(', ', $filterArgs);
    }

    /**
     * Build sort arguments string from array
     * 
     * @param array $sort Associative array with:
     *                   - 'fields': string|string[] Field(s) to sort by
     *                   - 'orders': string|string[] Sort order(s) for the fields
     * @return string The sort criteria formatted as GraphQL arguments
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
} 