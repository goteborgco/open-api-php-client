<?php

namespace GBGCO\Types\Entities;

/**
 * Entity representing a media item
 * 
 * This class represents a media item (image, video, etc.) with its metadata
 * such as credits, captions, and various size versions. It is used for
 * featured media, galleries, and other media content.
 */
class Media
{
    /** @var int The unique identifier of the media item */
    private int $id;

    /** @var string|null Attribution or credit for the media */
    private ?string $credit;

    /** @var string|null Caption or description of the media */
    private ?string $caption;

    /** @var string|null Alternative text for accessibility */
    private ?string $alt_text;

    /** @var string|null Type of media (e.g., 'image', 'video') */
    private ?string $media_type;

    /** @var string|null MIME type of the media file */
    private ?string $mime_type;

    /** @var MediaSizes|null Available size variations of the media */
    private ?MediaSizes $sizes;

    /**
     * Create a new media item from API data
     * 
     * @param array $data Raw media data from the API with keys:
     *                    - id: Media item ID
     *                    - credit: Attribution information
     *                    - caption: Media description
     *                    - alt_text: Alternative text
     *                    - media_type: Type of media
     *                    - mime_type: MIME type
     *                    - sizes: Array of available sizes
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->credit = $data['credit'] ?? null;
        $this->caption = $data['caption'] ?? null;
        $this->alt_text = $data['alt_text'] ?? null;
        $this->media_type = $data['media_type'] ?? null;
        $this->mime_type = $data['mime_type'] ?? null;
        $this->sizes = isset($data['sizes']) ? new MediaSizes($data['sizes']) : null;
    }

    /**
     * Get the unique identifier
     * 
     * @return int The media item's ID
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the attribution credit
     * 
     * @return string|null Credit information for the media
     */
    public function getCredit(): ?string
    {
        return $this->credit;
    }

    /**
     * Get the media caption
     * 
     * @return string|null Caption or description text
     */
    public function getCaption(): ?string
    {
        return $this->caption;
    }

    /**
     * Get the alternative text
     * 
     * @return string|null Alt text for accessibility
     */
    public function getAltText(): ?string
    {
        return $this->alt_text;
    }

    /**
     * Get the media type
     * 
     * @return string|null Type of media (e.g., 'image', 'video')
     */
    public function getMediaType(): ?string
    {
        return $this->media_type;
    }

    /**
     * Get the MIME type
     * 
     * @return string|null MIME type of the media file
     */
    public function getMimeType(): ?string
    {
        return $this->mime_type;
    }

    /**
     * Get available size variations
     * 
     * @return MediaSizes|null Object containing different size versions
     */
    public function getSizes(): ?MediaSizes
    {
        return $this->sizes;
    }
} 