<?php

declare(strict_types=1);


namespace Resolution\Changelog\Tables;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\SystemException;
use Bitrix\Main\UserTable;

class ChangelogTable extends DataManager
{
    public static function getTableName(): string
    {
        return 'resolution_changelogs';
    }

    /**
     * @return array
     * @throws ArgumentException
     * @throws SystemException
     */
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
            (new StringField('ENTITY_GROUP'))
                ->configureRequired(),
            (new StringField('OPERATION_TYPE'))
                ->configureRequired(),
            (new TextField('CHANGES'))
                ->configureRequired(),
            (new DatetimeField('EVENT_DATE_TIME'))
                ->configureRequired(),
            (new IntegerField('CREATED_BY'))
                ->configureRequired(),
            (new Reference(
                'CREATED_BY_USER',
                UserTable::class,
                Join::on('this.CREATED_BY', 'ref.ID')
            ))->configureJoinType('inner')
        ];
    }
}