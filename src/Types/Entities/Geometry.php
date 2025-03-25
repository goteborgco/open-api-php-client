<?php

namespace GBGCO\Types\Entities;

class Geometry
{
    private string $type;
    private array $coordinates;

    public function __construct(array $data)
    {
        $this->type = $data['type'] ?? '';
        $this->coordinates = $data['coordinates'] ?? [];
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getCoordinates(): array
    {
        return $this->coordinates;
    }

    public function getLatitude(): ?float
    {
        return $this->coordinates[1] ?? null;
    }

    public function getLongitude(): ?float
    {
        return $this->coordinates[0] ?? null;
    }
} 