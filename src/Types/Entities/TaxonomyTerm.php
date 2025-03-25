<?php

namespace GBGCO\Types\Entities;

/**
 * Entity representing a term within a taxonomy
 * 
 * This class represents a single term in a taxonomy, such as a category or tag,
 * with properties for its ID, usage count, name, description, and parent term.
 */
class TaxonomyTerm
{
    private int $id;
    private int $count;
    private string $name;
    private ?string $description;
    private ?int $parent;

    /**
     * Create a new taxonomy term from API data
     * 
     * @param array $data Raw term data from the API with keys:
     *                    - id: Term ID
     *                    - count: Number of items using this term
     *                    - name: Term name
     *                    - description: Optional term description
     *                    - parent: Optional parent term ID
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->count = $data['count'] ?? 0;
        $this->name = $data['name'] ?? '';
        $this->description = $data['description'] ?? null;
        $this->parent = isset($data['parent']) ? (int)$data['parent'] : null;
    }

    /**
     * Get the term's ID
     * 
     * @return int The unique identifier for this term
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the usage count
     * 
     * @return int Number of items using this term
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * Get the term's name
     * 
     * @return string The display name of this term
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the term's description
     * 
     * @return string|null The term's description, if any
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Get the parent term's ID
     * 
     * @return int|null The ID of the parent term, if this is a child term
     */
    public function getParent(): ?int
    {
        return $this->parent;
    }
} 