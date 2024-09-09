<?php

declare(strict_types=1);


namespace Resolution\Changelog\Channels\MySQL;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Resolution\Changelog\Channels\ReadChannelInterface;
use Resolution\Changelog\Event;
use Resolution\Changelog\Exceptions\EventReadException;
use Resolution\Changelog\Tables\ChangelogTable;
use Throwable;

class ReadChannel implements ReadChannelInterface
{
    public function getTotalEventsCount(string $entityName): int
    {
        try {
            return $this->getTotalEventsCountInternal($entityName);
        } catch (Throwable $e) {
            throw new EventReadException($e->getMessage(), $e->getCode(), $e);
        }
    }


    public function getEvents(string $entityName, int $page, int $pageSize): array
    {
        try {
            return $this->getEventsInternal($entityName, $page, $pageSize);
        } catch (Throwable $e) {
            throw new EventReadException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private function getTotalEventsCountInternal(string $entityName): int
    {
        return ChangelogTable::getCount([
            '=ENTITY_NAME' => $entityName,
        ]);
    }

    /**
     * @param string $entityName
     * @param int $page
     * @param int $pageSize
     * @return array
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws ArgumentException
     */
    private function getEventsInternal(string $entityName, int $page, int $pageSize): array
    {
        $rawEventsArray = ChangelogTable::query()
            ->setFilter(['=ENTITY_NAME' => $entityName])
            ->setOffset($page)
            ->setLimit($pageSize)
            ->exec()
            ->fetchAll();

        foreach ($rawEventsArray as $event) {
            $events[] = new Event(
                (int)$event['ENTITY_ID'],
                $event['ENTITY_NAME'],
                $event['OPERATION_TYPE'],
                $event['CHANGES'],
                (int)$event['TIMESTAMP'],
                (int)$event['CREATED_BY'],
            );
        }

        return $events ?? [];
    }
}