<?php

declare(strict_types=1);

namespace Resolution\Changelog;

use DateTimeImmutable;

final readonly class Event
{
    /**
     * Событие изменения сущности
     * @param int $entityId идентификатор сущности
     * @param string $entityName название сущности
     * @param string $entityGroup группа сущности (Используется для группирования информации по нескольким сущностям)
     * @param OperationTypeEnum $operationType тип операции
     * @param string $changes измененные данные (ВАЖНО! Нужно следить за размером строки в хранилище)
     * @param DateTimeImmutable $dateTime дата внесения изменений
     * @param int $createdBy кем создано
     */
    public function __construct(
        private int $entityId,
        private string $entityName,
        private string $entityGroup,
        private OperationTypeEnum $operationType,
        private string $changes,
        private DateTimeImmutable $dateTime,
        private int $createdBy
    ) {
    }

    public function getEntityId(): int
    {
        return $this->entityId;
    }

    public function getEntityName(): string
    {
        return $this->entityName;
    }

    public function getEntityGroup(): string
    {
        return $this->entityGroup;
    }

    public function getOperationType(): OperationTypeEnum
    {
        return $this->operationType;
    }

    public function getChanges(): string
    {
        return $this->changes;
    }

    public function getDateTime(): DateTimeImmutable
    {
        return $this->dateTime;
    }

    public function getCreatedBy(): int
    {
        return $this->createdBy;
    }

    public function toArray(): array
    {
        return [
            'entityId' => $this->getEntityId(),
            'entityName' => $this->getEntityName(),
            'entityGroup' => $this->getEntityGroup(),
            'operationType' => $this->getOperationType()->value,
            'changes' => $this->getChanges(),
            'dateTime' => $this->getDateTime()->format('Y-m-d H:i:s'),
            'createdBy' => $this->getCreatedBy()
        ];
    }
}