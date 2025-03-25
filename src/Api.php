<?php

namespace GBGCO;

use GBGCO\Types\Guides;
use GBGCO\Types\Events;
use GBGCO\Types\Places;
use GBGCO\Types\Search;
use GBGCO\Types\Taxonomies;
use GBGCO\Types\Taxonomy;

class Api
{
    private Client $client;
    private ?Guides $guides = null;
    private ?Events $events = null;
    private ?Places $places = null;
    private ?Search $search = null;
    private ?Taxonomies $taxonomies = null;
    private ?Taxonomy $taxonomy = null;

    public function __construct(string $apiUrl, string $subscriptionKey)
    {
        $this->client = new Client($apiUrl, $subscriptionKey);
    }

    /**
     * Execute a raw GraphQL query
     * 
     * @param string $query The complete GraphQL query
     * @return array The query result
     * @throws \Exception If the query fails
     */
    public function query(string $query): array
    {
        if (empty($query)) {
            throw new \InvalidArgumentException('Query cannot be empty');
        }

        return $this->client->execute($query);
    }

    /**
     * Get the Guides API
     */
    public function guides(): Guides
    {
        if ($this->guides === null) {
            $this->guides = new Guides($this->client);
        }
        return $this->guides;
    }

    public function events(): Events
    {
        if ($this->events === null) {
            $this->events = new Events($this->client);
        }
        return $this->events;
    }

    public function places(): Places
    {
        if ($this->places === null) {
            $this->places = new Places($this->client);
        }
        return $this->places;
    }

    /**
     * Get the Search API
     */
    public function search(): Search
    {
        if ($this->search === null) {
            $this->search = new Search($this->client);
        }
        return $this->search;
    }

    /**
     * Get the Taxonomies API
     */
    public function taxonomies(): Taxonomies
    {
        if ($this->taxonomies === null) {
            $this->taxonomies = new Taxonomies($this->client);
        }
        return $this->taxonomies;
    }

    /**
     * Get the Taxonomy API for querying specific taxonomies
     */
    public function taxonomy(): Taxonomy
    {
        if ($this->taxonomy === null) {
            $this->taxonomy = new Taxonomy($this->client);
        }
        return $this->taxonomy;
    }
} 