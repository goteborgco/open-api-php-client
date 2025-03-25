<?php

namespace GBGCO\Types\Entities;

class Translations
{
    private ?int $sv;
    private ?int $en;

    public function __construct(array $data)
    {
        $this->sv = $data['sv'] ?? null;
        $this->en = $data['en'] ?? null;
    }

    public function getSv(): ?int
    {
        return $this->sv;
    }

    public function getEn(): ?int
    {
        return $this->en;
    }
} 