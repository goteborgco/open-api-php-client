<?php

namespace GBGCO\Types\Entities;

/**
 * Entity representing different size variations of a media item
 * 
 * This class manages the various size versions of a media item (typically an image),
 * providing access to standard WordPress image sizes: medium, thumbnail, large,
 * and full size versions.
 */
class MediaSizes
{
    /** @var Image|null Medium size version of the media */
    private ?Image $medium;

    /** @var Image|null Thumbnail size version of the media */
    private ?Image $thumbnail;

    /** @var Image|null Large size version of the media */
    private ?Image $large;

    /** @var Image|null Full/original size version of the media */
    private ?Image $full;

    /**
     * Create a new media sizes collection from API data
     * 
     * @param array $data Raw size data from the API with keys:
     *                    - medium: Data for medium size version
     *                    - thumbnail: Data for thumbnail version
     *                    - large: Data for large size version
     *                    - full: Data for full size version
     */
    public function __construct(array $data)
    {
        $this->medium = isset($data['medium']) ? new Image($data['medium']) : null;
        $this->thumbnail = isset($data['thumbnail']) ? new Image($data['thumbnail']) : null;
        $this->large = isset($data['large']) ? new Image($data['large']) : null;
        $this->full = isset($data['full']) ? new Image($data['full']) : null;
    }

    /**
     * Get the medium size version
     * 
     * @return Image|null Medium size image data
     */
    public function getMedium(): ?Image
    {
        return $this->medium;
    }

    /**
     * Get the thumbnail version
     * 
     * @return Image|null Thumbnail size image data
     */
    public function getThumbnail(): ?Image
    {
        return $this->thumbnail;
    }

    /**
     * Get the large size version
     * 
     * @return Image|null Large size image data
     */
    public function getLarge(): ?Image
    {
        return $this->large;
    }

    /**
     * Get the full size version
     * 
     * @return Image|null Full/original size image data
     */
    public function getFull(): ?Image
    {
        return $this->full;
    }
} 