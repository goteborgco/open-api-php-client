<?php

namespace GBGCO\Types\Entities;

/**
 * Entity representing an image file
 * 
 * This class represents a specific image file with its dimensions and URL.
 * It is typically used as part of a MediaSizes collection to represent
 * different size versions of a media item.
 */
class Image
{
    /** @var int Width of the image in pixels */
    private int $width;

    /** @var int Height of the image in pixels */
    private int $height;

    /** @var string URL where the image can be accessed */
    private string $source_url;

    /**
     * Create a new image from API data
     * 
     * @param array $data Raw image data from the API with keys:
     *                    - width: Image width in pixels
     *                    - height: Image height in pixels
     *                    - source_url: URL to access the image
     */
    public function __construct(array $data)
    {
        $this->width = $data['width'] ?? 0;
        $this->height = $data['height'] ?? 0;
        $this->source_url = $data['source_url'] ?? '';
    }

    /**
     * Get the image width
     * 
     * @return int Width in pixels
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * Get the image height
     * 
     * @return int Height in pixels
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * Get the image URL
     * 
     * @return string URL where the image can be accessed
     */
    public function getSourceUrl(): string
    {
        return $this->source_url;
    }
} 