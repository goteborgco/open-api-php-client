<?php

namespace GBGCO\Types\Entities;

class Features
{
    private string $type;
    private Geometry $geometry;
    private Properties $properties;

    public function __construct(array $data)
    {
        $this->type = $data['type'] ?? '';
        $this->geometry = new Geometry($data['geometry'] ?? []);
        $this->properties = new Properties($data['properties'] ?? []);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getGeometry(): Geometry
    {
        return $this->geometry;
    }

    public function getProperties(): Properties
    {
        return $this->properties;
    }
} 