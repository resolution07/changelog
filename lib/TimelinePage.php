<?php

declare(strict_types=1);


namespace Resolution\Changelog;

final class TimelinePage
{
    /**
     * @param int $totalCount
     * @param array<Event> $events
     */
    public function __construct(
        private int $totalCount,
        private array $events
    ) {
    }

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    public function getEvents(): array
    {
        return $this->events;
    }
}