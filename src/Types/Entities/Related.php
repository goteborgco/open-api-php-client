<?php

namespace GBGCO\Types\Entities;

class Related
{
    /** @var WpEntity[] */
    private array $places;
    /** @var WpEntity[] */
    private array $guides;
    /** @var WpEntity[] */
    private array $events;

    public function __construct(array $data)
    {
        $this->places = array_map(fn($place) => new WpEntity($place), $data['places'] ?? []);
        $this->guides = array_map(fn($guide) => new WpEntity($guide), $data['guides'] ?? []);
        $this->events = array_map(fn($event) => new WpEntity($event), $data['events'] ?? []);
    }

    /** @return WpEntity[] */
    public function getPlaces(): array
    {
        return $this->places;
    }

    /** @return WpEntity[] */
    public function getGuides(): array
    {
        return $this->guides;
    }

    /** @return WpEntity[] */
    public function getEvents(): array
    {
        return $this->events;
    }
} 