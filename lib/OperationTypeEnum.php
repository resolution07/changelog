<?php

declare(strict_types=1);


namespace Resolution\Changelog;

enum OperationTypeEnum: string
{
    case CREATE = 'create';
    case UPDATE = 'update';
    case DELETE = 'delete';
}