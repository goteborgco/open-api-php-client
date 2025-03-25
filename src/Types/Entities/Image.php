<?php

namespace GBGCO\Types\Entities;

class Image
{
    private int $width;
    private int $height;
    private string $source_url;

    public function __construct(array $data)
    {
        $this->width = $data['width'] ?? 0;
        $this->height = $data['height'] ?? 0;
        $this->source_url = $data['source_url'] ?? '';
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getSourceUrl(): string
    {
        return $this->source_url;
    }
} 