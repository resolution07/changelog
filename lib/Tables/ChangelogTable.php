<?php

declare(strict_types=1);


namespace Resolution\Changelog\Tables;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;

class ChangelogTable extends DataManager
{
    public static function getTableName(): string
    {
        return 'resolution_changelogs';
    }

    public static function getMap(): array
    {
        return [
            (new IntegerField('ID'))
                ->configurePrimary()
                ->configureAutocomplete(),
            (new IntegerField('ENTITY_ID'))
                ->configureRequired(),
            (new StringField('ENTITY_NAME'))
                ->configureRequired(),
            (new StringField('OPERATION_TYPE'))
                ->configureRequired(),
            (new TextField('CHANGES'))
                ->configureRequired(),
            (new DatetimeField('EVENT_DATE_TIME'))
                ->configureRequired(),
            (new IntegerField('CREATED_BY'))
                ->configureRequired(),
        ];
    }
}