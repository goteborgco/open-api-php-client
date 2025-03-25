<?php

namespace GBGCO\Types\Entities;

/**
 * Entity representing a GeoJSON Feature
 * 
 * This class represents a feature in GeoJSON format, containing geometry
 * and property data. It follows the GeoJSON specification for representing
 * geographical features with their properties.
 */
class Features
{
    /** @var string The type of GeoJSON object (typically 'Feature') */
    private string $type;

    /** @var Geometry The geometric shape and coordinates of the feature */
    private Geometry $geometry;

    /** @var Properties Additional properties associated with the feature */
    private Properties $properties;

    /**
     * Create a new GeoJSON feature from API data
     * 
     * @param array $data Raw feature data from the API with keys:
     *                    - type: GeoJSON object type
     *                    - geometry: Geometric data
     *                    - properties: Feature properties
     */
    public function __construct(array $data)
    {
        $this->type = $data['type'] ?? '';
        $this->geometry = new Geometry($data['geometry'] ?? []);
        $this->properties = new Properties($data['properties'] ?? []);
    }

    /**
     * Get the GeoJSON object type
     * 
     * @return string The type of GeoJSON object
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Get the feature geometry
     * 
     * @return Geometry Object containing shape and coordinates
     */
    public function getGeometry(): Geometry
    {
        return $this->geometry;
    }

    /**
     * Get the feature properties
     * 
     * @return Properties Object containing additional feature data
     */
    public function getProperties(): Properties
    {
        return $this->properties;
    }
} 