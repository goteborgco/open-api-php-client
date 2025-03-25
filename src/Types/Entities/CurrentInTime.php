<?php

namespace GBGCO\Types\Entities;

class CurrentInTime
{
    private array $months;
    private array $weekdays;

    public function __construct(array $data)
    {
        $this->months = $data['months'] ?? [];
        $this->weekdays = $data['weekdays'] ?? [];
    }

    public function getMonths(): array
    {
        return $this->months;
    }

    public function getWeekdays(): array
    {
        return $this->weekdays;
    }
} 