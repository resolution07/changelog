<?php

declare(strict_types=1);


namespace Resolution\Changelog\Channels;

use Resolution\Changelog\Event;
use Resolution\Changelog\Exceptions\EventReadException;

/** Общий интерфейс каналов чтения */
interface ReadChannelInterface
{
    /**
     * Возвращает общее количество событий по указанной сущности
     * @param string $entityName
     * @return int
     * @throws EventReadException
     */
    public function getTotalEventsCount(string $entityName): int;

    /**
     * Возвращает список событий с пагинацией по указанной сущности
     * @param string $entityName
     * @param int $page
     * @param int $pageSize
     * @return array<Event>
     * @throws EventReadException
     */
    public function getEvents(string $entityName, int $page, int $pageSize): array;
}