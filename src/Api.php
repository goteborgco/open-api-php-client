<?php

namespace GBGCO;

use GBGCO\Types\Guides;
use GBGCO\Types\Events;
use GBGCO\Types\Places;
use GBGCO\Types\Search;
use GBGCO\Types\Taxonomies;
use GBGCO\Types\Taxonomy;

/**
 * Main API client class for interacting with the Göteborg & Co GraphQL API
 * 
 * This class provides access to all API endpoints through type-safe methods
 * and supports raw GraphQL queries for advanced use cases.
 */
class Api
{
    private Client $client;
    private ?Guides $guides = null;
    private ?Events $events = null;
    private ?Places $places = null;
    private ?Search $search = null;
    private ?Taxonomies $taxonomies = null;
    private ?Taxonomy $taxonomy = null;

    /**
     * Initialize the API client
     * 
     * @param string $apiUrl The base URL for the GraphQL API
     * @param string $subscriptionKey Your API subscription key
     */
    public function __construct(string $apiUrl, string $subscriptionKey)
    {
        $this->client = new Client($apiUrl, $subscriptionKey);
    }

    /**
     * Execute a raw GraphQL query
     * 
     * @param string $query The complete GraphQL query
     * @return array The query result
     * @throws \InvalidArgumentException If the query is empty
     * @throws \Exception If the query execution fails
     */
    public function query(string $query): array
    {
        if (empty($query)) {
            throw new \InvalidArgumentException('Query cannot be empty');
        }

        return $this->client->execute($query);
    }

    /**
     * Get the Guides API for accessing guide content
     * 
     * @return Guides The Guides API instance
     */
    public function guides(): Guides
    {
        if ($this->guides === null) {
            $this->guides = new Guides($this->client);
        }
        return $this->guides;
    }

    /**
     * Get the Events API for accessing event content
     * 
     * @return Events The Events API instance
     */
    public function events(): Events
    {
        if ($this->events === null) {
            $this->events = new Events($this->client);
        }
        return $this->events;
    }

    /**
     * Get the Places API for accessing place content
     * 
     * @return Places The Places API instance
     */
    public function places(): Places
    {
        if ($this->places === null) {
            $this->places = new Places($this->client);
        }
        return $this->places;
    }

    /**
     * Get the Search API for performing content searches
     * 
     * @return Search The Search API instance
     */
    public function search(): Search
    {
        if ($this->search === null) {
            $this->search = new Search($this->client);
        }
        return $this->search;
    }

    /**
     * Get the Taxonomies API for listing available taxonomies
     * 
     * @return Taxonomies The Taxonomies API instance
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
     * 
     * @return Taxonomy The Taxonomy API instance
     */
    public function taxonomy(): Taxonomy
    {
        if ($this->taxonomy === null) {
            $this->taxonomy = new Taxonomy($this->client);
        }
        return $this->taxonomy;
    }
} 