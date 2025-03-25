<?php

namespace GBGCO\Types\Entities;

/**
 * Entity representing a GeoJSON FeatureCollection for markers
 * 
 * This class represents a collection of GeoJSON features used for displaying
 * markers on a map. It follows the GeoJSON specification for FeatureCollection
 * objects, containing a type identifier and an array of features.
 */
class Markers
{
    /** @var string The GeoJSON object type (typically 'FeatureCollection') */
    private string $type;

    /** @var Features[] Array of GeoJSON Feature objects */
    private array $features;

    /**
     * Create a new markers collection from API data
     * 
     * @param array $data Raw markers data from the API with keys:
     *                    - type: GeoJSON object type
     *                    - features: Array of feature data
     */
    public function __construct(array $data)
    {
        $this->type = $data['type'] ?? '';
        $this->features = array_map(fn($feature) => new Features($feature), $data['features'] ?? []);
    }

    /**
     * Get the GeoJSON object type
     * 
     * @return string The type of GeoJSON object (typically 'FeatureCollection')
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Get the array of GeoJSON features
     * 
     * @return Features[] Array of Feature objects
     */
    public function getFeatures(): array
    {
        return $this->features;
    }
} 