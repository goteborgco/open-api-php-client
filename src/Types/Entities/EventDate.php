<?php

namespace GBGCO\Types\Entities;

/**
 * Entity representing a date range for an event
 * 
 * This class represents a time period with start and end dates,
 * typically used to specify when an event takes place. The dates
 * are stored in ISO 8601 format.
 */
class EventDate
{
    /** @var string|null Start date in ISO 8601 format */
    private ?string $start;

    /** @var string|null End date in ISO 8601 format */
    private ?string $end;

    /**
     * Create a new event date range from API data
     * 
     * @param array $data Raw date data from the API with keys:
     *                    - start: Start date in ISO 8601 format
     *                    - end: End date in ISO 8601 format
     */
    public function __construct(array $data)
    {
        $this->start = $data['start'] ?? null;
        $this->end = $data['end'] ?? null;
    }

    /**
     * Get the start date
     * 
     * @return string|null Start date in ISO 8601 format
     */
    public function getStart(): ?string
    {
        return $this->start;
    }

    /**
     * Get the end date
     * 
     * @return string|null End date in ISO 8601 format
     */
    public function getEnd(): ?string
    {
        return $this->end;
    }
} 