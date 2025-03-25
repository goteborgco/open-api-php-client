<?php

namespace GBGCO\Types\Entities;

class TaxonomyTerm
{
    private int $id;
    private int $count;
    private string $name;
    private ?string $description;
    private ?int $parent;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->count = $data['count'] ?? 0;
        $this->name = $data['name'] ?? '';
        $this->description = $data['description'] ?? null;
        $this->parent = isset($data['parent']) ? (int)$data['parent'] : null;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getParent(): ?int
    {
        return $this->parent;
    }
} 