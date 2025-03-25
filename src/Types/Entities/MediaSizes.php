<?php

namespace GBGCO\Types\Entities;

class MediaSizes
{
    private ?Image $medium;
    private ?Image $thumbnail;
    private ?Image $large;
    private ?Image $full;

    public function __construct(array $data)
    {
        $this->medium = isset($data['medium']) ? new Image($data['medium']) : null;
        $this->thumbnail = isset($data['thumbnail']) ? new Image($data['thumbnail']) : null;
        $this->large = isset($data['large']) ? new Image($data['large']) : null;
        $this->full = isset($data['full']) ? new Image($data['full']) : null;
    }

    public function getMedium(): ?Image
    {
        return $this->medium;
    }

    public function getThumbnail(): ?Image
    {
        return $this->thumbnail;
    }

    public function getLarge(): ?Image
    {
        return $this->large;
    }

    public function getFull(): ?Image
    {
        return $this->full;
    }
} 