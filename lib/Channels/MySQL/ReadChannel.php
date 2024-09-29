<?php

declare(strict_types=1);

namespace Resolution\Changelog\Channels\MySQL;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use DateTimeImmutable;
use Resolution\Changelog\Channels\ReadChannelInterface;
use Resolution\Changelog\Event;
use Resolution\Changelog\Exceptions\EventReadException;
use Resolution\Changelog\Filter;
use Resolution\Changelog\OperationTypeEnum;
use Resolution\Changelog\Tables\ChangelogTable;
use Throwable;

class ReadChannel implements ReadChannelInterface
{
    public function getTotalEventsCountByFilter(Filter $filter): int
    {
        try {
            return $this->getTotalEventsCountInternal($filter);
        } catch (Throwable $e) {
            throw new EventReadException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getEventsByFilter(Filter $filter, int $limit, int $offset): array
    {
        try {
            return $this->getEventsInternal($filter, $limit, $offset);
        } catch (Throwable $e) {
            throw new EventReadException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private function getTotalEventsCountInternal(Filter $filter): int
    {
        return ChangelogTable::getCount($filter->toArray());
    }

    /**
     * @param Filter $filter
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private function getEventsInternal(Filter $filter, int $limit, int $offset): array
    {
        $rawEventsArray = ChangelogTable::getList([
            'select' => ['*'],
            'filter' => $filter->toArray(),
            'order' => ['EVENT_DATE_TIME' => 'DESC'],
            'limit' => $limit,
            'offset' => $offset
        ])->fetchAll();

        foreach ($rawEventsArray as $event) {
            $events[] = new Event(
                (int)$event['ENTITY_ID'],
                $event['ENTITY_NAME'],
                $event['ENTITY_GROUP'],
                OperationTypeEnum::tryFrom($event['OPERATION_TYPE']) ?? OperationTypeEnum::UNDEFINED,
                $event['CHANGES'],
                (new DateTimeImmutable())->setTimestamp($event['EVENT_DATE_TIME']->getTimestamp()),
                (int)$event['CREATED_BY']
            );
        }
        return $events ?? [];
    }
}