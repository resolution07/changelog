<?php

declare(strict_types=1);


namespace Resolution\Changelog;

use Resolution\Changelog\Channels\ReadChannelInterface;
use Resolution\Changelog\Exceptions\EventReadException;

class Timeline
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
    public function getPage(string $entityName, int $offset = 1, int $limit = 10): TimelinePage
    {
        if ($offset < 1) {
            $offset = 1;
        }

        if ($limit < self::MIN_PAGE_SIZE) {
            $limit = self::MIN_PAGE_SIZE;
        }

        if ($limit > self::MAX_PAGE_SIZE) {
            $limit = self::MAX_PAGE_SIZE;
        }

        $totalCount = $this->channel->getTotalEventsCount($entityName);
        $events = $this->channel->getEvents($entityName, $offset, $limit);

        return new TimelinePage(
            $totalCount,
            $events
        );
    }

}