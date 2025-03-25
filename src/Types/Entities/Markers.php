<?php

namespace GBGCO\Types\Entities;

class Markers
{
    private string $type;
    /** @var Features[] */
    private array $features;

    public function __construct(array $data)
    {
        $this->type = $data['type'] ?? '';
        $this->features = array_map(fn($feature) => new Features($feature), $data['features'] ?? []);
    }

    public function getType(): string
    {
        return $this->type;
    }

    /** @return Features[] */
    public function getFeatures(): array
    {
        return $this->features;
    }
} 