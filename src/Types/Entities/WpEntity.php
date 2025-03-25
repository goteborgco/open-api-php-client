<?php

namespace GBGCO\Types\Entities;

/**
 * Entity representing a WordPress content item
 * 
 * This class represents a content item from WordPress, such as an event, place,
 * or guide. It includes common WordPress fields like title and content, as well
 * as custom fields specific to the Göteborg & Co platform.
 */
class WpEntity
{
    /** @var int The unique identifier of the content item */
    private int $id;
    
    /** @var string|null The creation date of the content item */
    private ?string $date;
    
    /** @var string|null The last modification date of the content item */
    private ?string $modified;
    
    /** @var string|null The content type (e.g., 'event', 'place', 'guide') */
    private ?string $type;
    
    /** @var string|null The URL to view the content item */
    private ?string $link;
    
    /** @var string|null The title of the content item */
    private ?string $title;
    
    /** @var string|null The full content/description */
    private ?string $content;
    
    /** @var string|null A short excerpt/summary of the content */
    private ?string $excerpt;
    
    /** @var array Array of category IDs this item belongs to */
    private array $categories;
    
    /** @var array Array of area IDs this item is associated with */
    private array $areas;
    
    /** @var array Array of tag IDs associated with this item */
    private array $tags;
    
    /** @var array Array of invisible tag IDs (for internal use) */
    private array $invisible_tags;
    
    /** @var string The language code of the content ('en'|'sv') */
    private string $lang;
    
    /** @var Translations|null Available translations of this content */
    private ?Translations $translations;
    
    /** @var array Array of Media objects for featured media */
    private array $featuredmedia;
    
    /** @var string|null Optional heading for the item's category */
    private ?string $category_heading;
    
    /** @var array Array of Media objects for the gallery */
    private array $gallery;
    
    /** @var Contact|null Contact information associated with this item */
    private ?Contact $contact;
    
    /** @var Location|null Location information for this item */
    private ?Location $location;
    
    /** @var bool|null Whether admission is free (for events) */
    private ?bool $free;
    
    /** @var array Array of EventDate objects for event dates */
    private array $dates;
    
    /** @var int|null Associated place ID (for events) */
    private ?int $place_id;
    
    /** @var int|null Classification value for the item */
    private ?int $classification;
    
    /** @var CurrentInTime|null Current time status information */
    private ?CurrentInTime $currentInTime;

    /**
     * Create a new WordPress entity from API data
     * 
     * @param array $data Raw entity data from the API with keys matching property names
     */
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

    /**
     * Get the ID.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the date.
     *
     * @return string|null
     */
    public function getDate(): ?string
    {
        return $this->date;
    }

    /**
     * Get the modified date.
     *
     * @return string|null
     */
    public function getModified(): ?string
    {
        return $this->modified;
    }

    /**
     * Get the type.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Get the link.
     *
     * @return string|null
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * Get the title.
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Get the content.
     *
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Get the excerpt.
     *
     * @return string|null
     */
    public function getExcerpt(): ?string
    {
        return $this->excerpt;
    }

    /**
     * Get categories.
     *
     * @return array
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * Get areas.
     *
     * @return array
     */
    public function getAreas(): array
    {
        return $this->areas;
    }

    /**
     * Get tags.
     *
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * Get invisible tags.
     *
     * @return array
     */
    public function getInvisibleTags(): array
    {
        return $this->invisible_tags;
    }

    /**
     * Get language.
     *
     * @return string
     */
    public function getLang(): string
    {
        return $this->lang;
    }

    /**
     * Get translations.
     *
     * @return Translations|null
     */
    public function getTranslations(): ?Translations
    {
        return $this->translations;
    }

    /**
     * Get featured media.
     *
     * @return array
     */
    public function getFeaturedMedia(): array
    {
        return $this->featuredmedia;
    }

    /**
     * Get category heading.
     *
     * @return string|null
     */
    public function getCategoryHeading(): ?string
    {
        return $this->category_heading;
    }

    /**
     * Get gallery images.
     *
     * @return array
     */
    public function getGallery(): array
    {
        return $this->gallery;
    }

    /**
     * Get contact details.
     *
     * @return Contact|null
     */
    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    /**
     * Get location.
     *
     * @return Location|null
     */
    public function getLocation(): ?Location
    {
        return $this->location;
    }

    /**
     * Check if the event is free.
     *
     * @return bool|null
     */
    public function isFree(): ?bool
    {
        return $this->free;
    }

    /**
     * Get event dates.
     *
     * @return array
     */
    public function getDates(): array
    {
        return $this->dates;
    }

    /**
     * Get place ID.
     *
     * @return int|null
     */
    public function getPlaceId(): ?int
    {
        return $this->place_id;
    }

    /**
     * Get classification.
     *
     * @return int|null
     */
    public function getClassification(): ?int
    {
        return $this->classification;
    }

    /**
     * Get current in time.
     *
     * @return CurrentInTime|null
     */
    public function getCurrentInTime(): ?CurrentInTime
    {
        return $this->currentInTime;
    }
} 