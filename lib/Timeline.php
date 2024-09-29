<?php

declare(strict_types=1);

namespace Resolution\Changelog;

use Resolution\Changelog\Channels\ReadChannelInterface;
use Resolution\Changelog\Exceptions\EventReadException;

final readonly class Timeline
{
    public const MIN_PAGE_SIZE = 0;
    public const MAX_PAGE_SIZE = 50;
    private ReadChannelInterface $channel;

    public function __construct(ReadChannelInterface $channel)
    {
        $this->channel = $channel;
    }

    /**
     * Возвращает страницу с событиями сущности и пагинацией
     * @param Filter $filter
     * @param int $limit
     * @param int $offset
     * @return TimelinePage
     * @throws EventReadException
     */
    public function getPageByFilter(Filter $filter, int $limit, int $offset): TimelinePage
    {
        if ($limit < self::MIN_PAGE_SIZE) {
            $limit = self::MIN_PAGE_SIZE;
        }

        if ($limit > self::MAX_PAGE_SIZE) {
            $limit = self::MAX_PAGE_SIZE;
        }

        if ($offset < 0) {
            $offset = 0;
        }

        $totalCount = $this->channel->getTotalEventsCountByFilter($filter);
        $events = $this->channel->getEventsByFilter($filter, $limit, $offset);

        return new TimelinePage(
            $totalCount,
            $events
        );
    }
}