<?php

namespace GBGCO\Types\Entities;

/**
 * Entity representing GeoJSON geometry
 * 
 * This class represents the geometric shape and coordinates of a GeoJSON feature.
 * It supports various geometry types like Point, LineString, Polygon, etc., with
 * their corresponding coordinate structures.
 */
class Geometry
{
    /** @var string The type of geometry (e.g., 'Point', 'LineString', 'Polygon') */
    private string $type;

    /** @var array The coordinates of the geometry in GeoJSON format */
    private array $coordinates;

    /**
     * Create a new geometry from API data
     * 
     * @param array $data Raw geometry data from the API with keys:
     *                    - type: Geometry type
     *                    - coordinates: Array of coordinates
     */
    public function __construct(array $data)
    {
        $this->type = $data['type'] ?? '';
        $this->coordinates = $data['coordinates'] ?? [];
    }

    /**
     * Get the geometry type
     * 
     * @return string The type of geometry (e.g., 'Point', 'LineString', 'Polygon')
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Get the raw coordinates array
     * 
     * @return array The coordinates in GeoJSON format
     */
    public function getCoordinates(): array
    {
        return $this->coordinates;
    }

    /**
     * Get the latitude component of a Point geometry
     * 
     * @return float|null The latitude value, or null if not a Point or coordinates are invalid
     */
    public function getLatitude(): ?float
    {
        return $this->coordinates[1] ?? null;
    }

    /**
     * Get the longitude component of a Point geometry
     * 
     * @return float|null The longitude value, or null if not a Point or coordinates are invalid
     */
    public function getLongitude(): ?float
    {
        return $this->coordinates[0] ?? null;
    }
} 