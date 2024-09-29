<?php

declare(strict_types=1);

namespace Resolution\Changelog;

readonly class Filter
{
    public function __construct(
        private string $entityName,
        private string $entityGroup
    ) {
    }

    public function getEntityName(): string
    {
        return $this->entityName;
    }

    public function getEntityGroup(): string
    {
        return $this->entityGroup;
    }

    public function toArray(): array
    {
        $filterArray = [];
        if ($this->getEntityName() !== '') {
            $filterArray['=ENTITY_NAME'] = $this->getEntityName();
        }

        if ($this->getEntityGroup() !== '') {
            $filterArray['=ENTITY_GROUP'] = $this->getEntityGroup();
        }

        return $filterArray;
    }
}