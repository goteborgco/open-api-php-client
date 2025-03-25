<?php

namespace GBGCO\Types\Entities;

/**
 * Entity representing a physical location
 * 
 * This class represents a location with its address components, geographical
 * coordinates, and additional metadata. It can be used for places, events,
 * or any other content type that has a physical location.
 */
class Location
{
    /** @var string|null Full formatted address */
    private ?string $address;

    /** @var float|null Latitude coordinate */
    private ?float $lat;

    /** @var float|null Longitude coordinate */
    private ?float $lng;

    /** @var int|null Default zoom level for map display */
    private ?int $zoom;

    /** @var string|null Google Places ID for this location */
    private ?string $place_id;

    /** @var string|null Location name or establishment name */
    private ?string $name;

    /** @var string|null Street number component of the address */
    private ?string $street_number;

    /** @var string|null Street name component of the address */
    private ?string $street_name;

    /** @var string|null State/region component of the address */
    private ?string $state;

    /** @var string|null Postal code component of the address */
    private ?string $post_code;

    /** @var string|null Full country name */
    private ?string $country;

    /** @var string|null Two-letter country code (ISO 3166-1 alpha-2) */
    private ?string $country_short;

    /**
     * Create a new location from API data
     * 
     * @param array $data Raw location data from the API with keys:
     *                    - address: Full formatted address
     *                    - lat: Latitude coordinate
     *                    - lng: Longitude coordinate
     *                    - zoom: Default map zoom level
     *                    - place_id: Google Places ID
     *                    - name: Location name
     *                    - street_number: Street number
     *                    - street_name: Street name
     *                    - state: State/region
     *                    - post_code: Postal code
     *                    - country: Full country name
     *                    - country_short: Two-letter country code
     */
    public function __construct(array $data)
    {
        $this->address = $data['address'] ?? null;
        $this->lat = $data['lat'] ?? null;
        $this->lng = $data['lng'] ?? null;
        $this->zoom = $data['zoom'] ?? null;
        $this->place_id = $data['place_id'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->street_number = $data['street_number'] ?? null;
        $this->street_name = $data['street_name'] ?? null;
        $this->state = $data['state'] ?? null;
        $this->post_code = $data['post_code'] ?? null;
        $this->country = $data['country'] ?? null;
        $this->country_short = $data['country_short'] ?? null;
    }

    /**
     * Get the full formatted address
     * 
     * @return string|null Complete address string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * Get the latitude coordinate
     * 
     * @return float|null Latitude in decimal degrees
     */
    public function getLat(): ?float
    {
        return $this->lat;
    }

    /**
     * Get the longitude coordinate
     * 
     * @return float|null Longitude in decimal degrees
     */
    public function getLng(): ?float
    {
        return $this->lng;
    }

    /**
     * Get the default map zoom level
     * 
     * @return int|null Zoom level for map display
     */
    public function getZoom(): ?int
    {
        return $this->zoom;
    }

    /**
     * Get the Google Places ID
     * 
     * @return string|null Unique identifier in Google Places
     */
    public function getPlaceId(): ?string
    {
        return $this->place_id;
    }

    /**
     * Get the location name
     * 
     * @return string|null Name of the location or establishment
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Get the street number
     * 
     * @return string|null Street number component of the address
     */
    public function getStreetNumber(): ?string
    {
        return $this->street_number;
    }

    /**
     * Get the street name
     * 
     * @return string|null Street name component of the address
     */
    public function getStreetName(): ?string
    {
        return $this->street_name;
    }

    /**
     * Get the state/region
     * 
     * @return string|null State or region component of the address
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * Get the postal code
     * 
     * @return string|null Postal code component of the address
     */
    public function getPostCode(): ?string
    {
        return $this->post_code;
    }

    /**
     * Get the country name
     * 
     * @return string|null Full name of the country
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * Get the country code
     * 
     * @return string|null Two-letter country code (ISO 3166-1 alpha-2)
     */
    public function getCountryShort(): ?string
    {
        return $this->country_short;
    }
} 