<?php

declare(strict_types=1);


namespace Resolution\Changelog\Channels;

use Resolution\Changelog\Event;
use Resolution\Changelog\Exceptions\EventReadException;
use Resolution\Changelog\Filter;

/** Общий интерфейс каналов чтения */
interface ReadChannelInterface
{
    /**
     * Возвращает общее количество событий по указанной сущности
     * @param Filter $filter
     * @return int
     * @throws EventReadException
     */
    public function getTotalEventsCountByFilter(Filter $filter): int;

    /**
     * Возвращает список событий с пагинацией по указанной сущности
     * @param Filter $filter
     * @param int $limit
     * @param int $offset
     * @return array<Event>
     * @throws EventReadException
     */
    public function getEventsByFilter(Filter $filter, int $limit, int $offset): array;
}