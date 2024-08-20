<?php

declare(strict_types=1);


namespace Resolution\Changelog;

use Resolution\Changelog\Exceptions\MaximumChangesSizeException;

final readonly class Event
{
    /**
     * @param int $entityId идентификатор сущности
     * @param string $entityName название сущности
     * @param OperationTypeEnum $operationType тип операции
     * @param string $changes измененные данные
     * ВАЖНО! Нужно следить за размером строки в хранилище.
     * @param int $updatedAt временная метка изменений
     * @param int $updatedBy кем изменено
     */
    public function __construct(
        private int $entityId,
        private string $entityName,
        private OperationTypeEnum $operationType,
        private string $changes,
        private int $updatedAt,
        private int $updatedBy
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

    public function getUpdatedAt(): int
    {
        return $this->updatedAt;
    }

    public function getUpdatedBy(): int
    {
        return $this->updatedBy;
    }
}