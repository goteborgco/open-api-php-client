<?php

namespace GBGCO\Types\Entities;

class WpEntity
{
    private int $id;
    private ?string $date;
    private ?string $modified;
    private ?string $type;
    private ?string $link;
    private ?string $title;
    private ?string $content;
    private ?string $excerpt;
    private array $categories;
    private array $areas;
    private array $tags;
    private array $invisible_tags;
    private string $lang;
    private ?Translations $translations;
    private array $featuredmedia;
    private ?string $category_heading;
    private array $gallery;
    private ?Contact $contact;
    private ?Location $location;
    private ?bool $free;
    private array $dates;
    private ?int $place_id;
    private ?int $classification;
    private ?CurrentInTime $currentInTime;

    public function __construct(array $data)
    {   
        $this->id = $data['id'] ?? 0;
        $this->date = $data['date'] ?? null;
        $this->modified = $data['modified'] ?? null;
        $this->type = $data['type'] ?? null;
        $this->link = $data['link'] ?? null;
        $this->title = $data['title'] ?? null;
        $this->content = $data['content'] ?? null;
        $this->excerpt = $data['excerpt'] ?? null;
        $this->categories = $data['categories'] ?? [];
        $this->areas = $data['areas'] ?? [];
        $this->tags = $data['tags'] ?? [];
        $this->invisible_tags = $data['invisible_tags'] ?? [];
        $this->lang = $data['lang'] ?? 'sv';
        $this->translations = isset($data['translations']) ? new Translations($data['translations']) : null;
        $this->featuredmedia = array_map(fn($media) => new Media($media), $data['featuredmedia'] ?? []);
        $this->category_heading = $data['category_heading'] ?? null;
        $this->gallery = array_map(fn($media) => new Media($media), $data['gallery'] ?? []);
        $this->contact = isset($data['contact']) ? new Contact($data['contact']) : null;
        $this->location = isset($data['location']) ? new Location($data['location']) : null;
        $this->free = $data['free'] ?? null;
        $this->dates = array_map(fn($date) => new EventDate($date), $data['dates'] ?? []);
        $this->place_id = $data['place_id'] ?? null;
        $this->classification = $data['classification'] ?? null;
        $this->currentInTime = isset($data['currentInTime']) ? new CurrentInTime($data['currentInTime']) : null;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function getModified(): ?string
    {
        return $this->modified;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getExcerpt(): ?string
    {
        return $this->excerpt;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function getAreas(): array
    {
        return $this->areas;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function getInvisibleTags(): array
    {
        return $this->invisible_tags;
    }

    public function getLang(): string
    {
        return $this->lang;
    }

    public function getTranslations(): ?Translations
    {
        return $this->translations;
    }

    public function getFeaturedMedia(): array
    {
        return $this->featuredmedia;
    }

    public function getCategoryHeading(): ?string
    {
        return $this->category_heading;
    }

    public function getGallery(): array
    {
        return $this->gallery;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function isFree(): ?bool
    {
        return $this->free;
    }

    public function getDates(): array
    {
        return $this->dates;
    }

    public function getPlaceId(): ?int
    {
        return $this->place_id;
    }

    public function getClassification(): ?int
    {
        return $this->classification;
    }

    public function getCurrentInTime(): ?CurrentInTime
    {
        return $this->currentInTime;
    }
} 