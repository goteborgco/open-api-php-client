<?php

namespace GBGCO\Traits;

trait GraphQLFieldsTrait
{
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
} 