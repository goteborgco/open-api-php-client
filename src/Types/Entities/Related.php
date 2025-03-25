<?php

namespace GBGCO\Types\Entities;

/**
 * Entity representing related content items
 * 
 * This class represents a collection of related content items from different
 * content types (places, guides, events). It is used to group related content
 * that should be displayed together or referenced from a main content item.
 */
class Related
{
    /** @var WpEntity[] Array of related place entities */
    private array $places;

    /** @var WpEntity[] Array of related guide entities */
    private array $guides;

    /** @var WpEntity[] Array of related event entities */
    private array $events;

    /**
     * Create a new related content collection from API data
     * 
     * @param array $data Raw related content data from the API with keys:
     *                    - places: Array of place data
     *                    - guides: Array of guide data
     *                    - events: Array of event data
     */
    public function __construct(array $data)
    {
        $this->places = array_map(fn($place) => new WpEntity($place), $data['places'] ?? []);
        $this->guides = array_map(fn($guide) => new WpEntity($guide), $data['guides'] ?? []);
        $this->events = array_map(fn($event) => new WpEntity($event), $data['events'] ?? []);
    }

    /**
     * Get the array of related places
     * 
     * @return WpEntity[] Array of place entities
     */
    public function getPlaces(): array
    {
        return $this->places;
    }

    /**
     * Get the array of related guides
     * 
     * @return WpEntity[] Array of guide entities
     */
    public function getGuides(): array
    {
        return $this->guides;
    }

    /**
     * Get the array of related events
     * 
     * @return WpEntity[] Array of event entities
     */
    public function getEvents(): array
    {
        return $this->events;
    }
} 