<?php

namespace GBGCO\Types\Entities;

/**
 * Entity representing GeoJSON feature properties
 * 
 * This class represents the non-geometric properties of a GeoJSON feature,
 * including identifiers, display information, and type classification.
 * These properties provide metadata and display attributes for the feature.
 */
class Properties
{
    /** @var string The display name of the feature */
    private string $name;

    /** @var int The unique identifier of the feature */
    private int $id;

    /** @var string URL or identifier for the feature's icon */
    private string $icon;

    /** @var string URL or path to the feature's thumbnail image */
    private string $thumbnail;

    /** @var string The classification type of the feature */
    private string $type;

    /** @var string URL-friendly slug identifier for the feature */
    private string $slug;

    /**
     * Create a new properties object from API data
     * 
     * @param array $data Raw properties data from the API with keys:
     *                    - name: Display name
     *                    - id: Unique identifier
     *                    - icon: Icon URL/identifier
     *                    - thumbnail: Thumbnail image URL
     *                    - type: Feature type
     *                    - slug: URL-friendly identifier
     */
    public function __construct(array $data)
    {
        $this->name = $data['name'] ?? '';
        $this->id = (int)($data['id'] ?? 0);
        $this->icon = $data['icon'] ?? '';
        $this->thumbnail = $data['thumbnail'] ?? '';
        $this->type = $data['type'] ?? '';
        $this->slug = $data['slug'] ?? '';
    }

    /**
     * Get the feature's display name
     * 
     * @return string The name of the feature
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the feature's unique identifier
     * 
     * @return int The unique ID of the feature
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the feature's icon URL/identifier
     * 
     * @return string The icon URL or identifier
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * Get the feature's thumbnail image URL
     * 
     * @return string The thumbnail image URL
     */
    public function getThumbnail(): string
    {
        return $this->thumbnail;
    }

    /**
     * Get the feature's classification type
     * 
     * @return string The type of the feature
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Get the feature's URL-friendly slug
     * 
     * @return string The URL-friendly identifier
     */
    public function getSlug(): string
    {
        return $this->slug;
    }
} 