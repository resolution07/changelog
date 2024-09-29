<?php

declare(strict_types=1);


namespace Resolution\Changelog\Channels\MySQL;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Resolution\Changelog\Channels\ReadChannelInterface;
use Resolution\Changelog\Event;
use Resolution\Changelog\Exceptions\EventReadException;
use Resolution\Changelog\OperationTypeEnum;
use Resolution\Changelog\Tables\ChangelogTable;
use DateTimeImmutable;
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

    public function getEvents(string $entityName, int $limit, int $offset): array
    {
        try {
            return $this->getEventsInternal($entityName, $limit, $offset);
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
     * @param int $offset
     * @param int $limit
     * @return array
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private function getEventsInternal(string $entityName, int $limit, int $offset): array
    {
        $rawEventsArray = ChangelogTable::getList([
            'select' => ['*'],
            'filter' => ['=ENTITY_NAME' => $entityName],
            'order' => ['EVENT_DATE_TIME' => 'DESC'],
            'limit' => $limit,
            'offset' => $offset
        ])->fetchAll();
        foreach ($rawEventsArray as $event) {
            $events[] = new Event(
                (int)$event['ENTITY_ID'],
                $event['ENTITY_NAME'],
                OperationTypeEnum::tryFrom($event['OPERATION_TYPE']) ?? OperationTypeEnum::UNDEFINED,
                $event['CHANGES'],
                (new DateTimeImmutable())->setTimestamp($event['EVENT_DATE_TIME']->getTimestamp()),
                (int)$event['CREATED_BY']
            );
        }
        return $events ?? [];
    }
}