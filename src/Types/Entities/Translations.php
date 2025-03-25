<?php

namespace GBGCO\Types\Entities;

/**
 * Entity representing content translations
 * 
 * This class represents the available translations for a content item,
 * storing the IDs of corresponding content items in different languages
 * (Swedish and English).
 */
class Translations
{
    /** @var int|null ID of the Swedish version of the content */
    private ?int $sv;

    /** @var int|null ID of the English version of the content */
    private ?int $en;

    /**
     * Create a new translations object from API data
     * 
     * @param array $data Raw translations data from the API with keys:
     *                    - sv: ID of Swedish content version
     *                    - en: ID of English content version
     */
    public function __construct(array $data)
    {
        $this->sv = $data['sv'] ?? null;
        $this->en = $data['en'] ?? null;
    }

    /**
     * Get the ID of the Swedish content version
     * 
     * @return int|null The content ID, or null if no Swedish version exists
     */
    public function getSv(): ?int
    {
        return $this->sv;
    }

    /**
     * Get the ID of the English content version
     * 
     * @return int|null The content ID, or null if no English version exists
     */
    public function getEn(): ?int
    {
        return $this->en;
    }
} 