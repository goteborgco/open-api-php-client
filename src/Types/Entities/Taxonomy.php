<?php

namespace GBGCO\Types\Entities;

/**
 * Entity representing a taxonomy type
 * 
 * This class represents a taxonomy classification type in the system,
 * such as 'categories', 'tags', or other custom taxonomies. It includes
 * information about the taxonomy's name, description, and supported content types.
 */
class Taxonomy
{
    private string $name;
    private ?string $description;
    private ?string $value;
    private array $types;

    /**
     * Create a new taxonomy from API data
     * 
     * @param array $data Raw taxonomy data from the API with keys:
     *                    - name: Taxonomy name (e.g. 'categories', 'tags')
     *                    - description: Optional taxonomy description
     *                    - value: Optional taxonomy value
     *                    - types: Array of content types that can use this taxonomy
     */
    public function __construct(array $data)
    {
        $this->name = $data['name'] ?? '';
        $this->description = $data['description'] ?? null;
        $this->value = $data['value'] ?? null;
        $this->types = $data['types'] ?? [];
    }

    /**
     * Get the taxonomy's name
     * 
     * @return string The name of this taxonomy (e.g. 'categories', 'tags')
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the taxonomy's description
     * 
     * @return string|null The taxonomy's description, if any
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Get the taxonomy's value
     * 
     * @return string|null The taxonomy's value, if any
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * Get the content types that can use this taxonomy
     * 
     * @return array Array of content type names that support this taxonomy
     */
    public function getTypes(): array
    {
        return $this->types;
    }
} 