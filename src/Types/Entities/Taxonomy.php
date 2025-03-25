<?php

namespace GBGCO\Types\Entities;

class Taxonomy
{
    private string $name;
    private ?string $description;
    private ?string $value;
    private array $types;

    public function __construct(array $data)
    {
        $this->name = $data['name'] ?? '';
        $this->description = $data['description'] ?? null;
        $this->value = $data['value'] ?? null;
        $this->types = $data['types'] ?? [];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getTypes(): array
    {
        return $this->types;
    }
} 