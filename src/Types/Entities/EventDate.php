<?php

namespace GBGCO\Types\Entities;

class EventDate
{
    private ?string $start;
    private ?string $end;

    public function __construct(array $data)
    {
        $this->start = $data['start'] ?? null;
        $this->end = $data['end'] ?? null;
    }

    public function getStart(): ?string
    {
        return $this->start;
    }

    public function getEnd(): ?string
    {
        return $this->end;
    }
} 