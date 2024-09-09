<?php

declare(strict_types=1);


namespace Resolution\Changelog;


final readonly class Event
{
    /**
     * @param int $entityId идентификатор сущности
     * @param string $entityName название сущности
     * @param OperationTypeEnum $operationType тип операции
     * @param string $changes измененные данные (ВАЖНО! Нужно следить за размером строки в хранилище)
     * @param int $timestamp временная метка изменений
     * @param int $createdBy кем создано
     */
    public function __construct(
        private int $entityId,
        private string $entityName,
        private OperationTypeEnum $operationType,
        private string $changes,
        private int $timestamp,
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

    public function getOperationType(): OperationTypeEnum
    {
        return $this->operationType;
    }

    public function getChanges(): string
    {
        return $this->changes;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    public function getCreatedBy(): int
    {
        return $this->createdBy;
    }
}