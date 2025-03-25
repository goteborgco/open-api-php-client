<?php

namespace GBGCO\Types\Entities;

class Media
{
    private int $id;
    private ?string $credit;
    private ?string $caption;
    private ?string $alt_text;
    private ?string $media_type;
    private ?string $mime_type;
    private ?MediaSizes $sizes;

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

    public function getId(): int
    {
        return $this->id;
    }

    public function getCredit(): ?string
    {
        return $this->credit;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function getAltText(): ?string
    {
        return $this->alt_text;
    }

    public function getMediaType(): ?string
    {
        return $this->media_type;
    }

    public function getMimeType(): ?string
    {
        return $this->mime_type;
    }

    public function getSizes(): ?MediaSizes
    {
        return $this->sizes;
    }
} 