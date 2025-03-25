<?php

namespace GBGCO\Types\Entities;

/**
 * Entity representing temporal availability information
 * 
 * This class represents when a content item is available or active,
 * specified by months and days of the week. It is typically used
 * for seasonal events or places with varying availability.
 */
class CurrentInTime
{
    /** @var array Array of month numbers (1-12) when the item is active */
    private array $months;

    /** @var array Array of weekday numbers (0-6, Monday-Sunday) when the item is active */
    private array $weekdays;

    /**
     * Create a new temporal availability set from API data
     * 
     * @param array $data Raw availability data from the API with keys:
     *                    - months: Array of active months (1-12)
     *                    - weekdays: Array of active weekdays (0-6)
     */
    public function __construct(array $data)
    {
        $this->months = $data['months'] ?? [];
        $this->weekdays = $data['weekdays'] ?? [];
    }

    /**
     * Get active months
     * 
     * @return array Array of month numbers (1-12) when the item is active
     */
    public function getMonths(): array
    {
        return $this->months;
    }

    /**
     * Get active weekdays
     * 
     * @return array Array of weekday numbers (0-6, Sunday-Saturday) when the item is active
     */
    public function getWeekdays(): array
    {
        return $this->weekdays;
    }
} 