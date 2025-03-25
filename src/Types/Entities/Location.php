<?php

namespace GBGCO\Types\Entities;

class Location
{
    private ?string $address;
    private ?float $lat;
    private ?float $lng;
    private ?int $zoom;
    private ?string $place_id;
    private ?string $name;
    private ?string $street_number;
    private ?string $street_name;
    private ?string $state;
    private ?string $post_code;
    private ?string $country;
    private ?string $country_short;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function getLng(): ?float
    {
        return $this->lng;
    }

    public function getZoom(): ?int
    {
        return $this->zoom;
    }

    public function getPlaceId(): ?string
    {
        return $this->place_id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getStreetNumber(): ?string
    {
        return $this->street_number;
    }

    public function getStreetName(): ?string
    {
        return $this->street_name;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function getPostCode(): ?string
    {
        return $this->post_code;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function getCountryShort(): ?string
    {
        return $this->country_short;
    }
} 