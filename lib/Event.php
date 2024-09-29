<?php

declare(strict_types=1);


namespace Resolution\Changelog;


use DateTimeImmutable;

final readonly class Event
{
    /**
     * @param int $entityId идентификатор сущности
     * @param string $entityName название сущности
     * @param OperationTypeEnum $operationType тип операции
     * @param string $changes измененные данные (ВАЖНО! Нужно следить за размером строки в хранилище)
     * @param DateTimeImmutable $dateTime дата внесения изменений
     * @param int $createdBy кем создано
     */
    public function __construct(
        private int $entityId,
        private string $entityName,
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
            'entityId' => $this->entityId,
            'entityName' => $this->entityName,
            'operationType' => $this->getOperationType()->value,
            'changes' => $this->getChanges(),
            'dateTime' => $this->dateTime->format('Y-m-d H:i:s'),
            'createdBy' => $this->createdBy,
        ];
    }
}