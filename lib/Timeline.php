<?php

declare(strict_types=1);


namespace Resolution\Changelog;

use Resolution\Changelog\Channels\ReadChannelInterface;
use Resolution\Changelog\Exceptions\EventReadException;

final readonly class Timeline
{
    public const int MIN_PAGE_SIZE = 10;
    public const int MAX_PAGE_SIZE = 50;
    private ReadChannelInterface $channel;

    public function __construct(ReadChannelInterface $channel)
    {
        $this->channel = $channel;
    }

    /**
     * Возвращает страницу с событиями сущности и пагинацией
     * @param string $entityName
     * @param int $offset
     * @param int $limit
     * @return TimelinePage
     * @throws EventReadException
     */
    public function getPage(string $entityName, int $limit, int $offset): TimelinePage
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
        $totalCount = $this->channel->getTotalEventsCount($entityName);
        $events = $this->channel->getEvents($entityName, $limit, $offset);
        return new TimelinePage(
            $totalCount,
            $events
        );
    }
}