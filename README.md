# Модуль Changelog

Модуль **Changelog** предоставляет API отслеживания изменений ваших сущностей и ведения
их журнала.

## Установка

Установите модуль через Composer:

```bash
composer require resolution07/changelog

```

## Использование

## Добавление события

Ниже приведен пример использования модуля Resolution\Changelog для записи события в журнал изменений по каналу MySQL:

```php
<?php
declare(strict_types=1);
use Bitrix\Main\Engine\CurrentUser;
use Resolution\Changelog\Changelog;
use Resolution\Changelog\Channels\MySQL\WriteChannel;
use Resolution\Changelog\Event;
use Resolution\Changelog\OperationTypeEnum;
$event = new Event(
    1,
    'someEntity',
    OperationTypeEnum::CREATE,
    json_encode(['someField' => 'someValue'], JSON_THROW_ON_ERROR),
    new DateTimeImmutable(),
    (int)CurrentUser::get()->getId()
);
$result = (new Changelog([new WriteChannel()]))
    ->sendEvent($event)
    ->getResult();
echo (int)$result->isSuccess();
```

#### Параметры:

- ID сущности (int): Уникальный идентификатор сущности, которая регистрируется.
- Тип сущности (string): Строковый идентификатор типа сущности (например, test_entity).
- Тип операции (OperationTypeEnum): Тип выполненной операции (CREATE, UPDATE, DELETE, UNDEFINED).
- Данные (string): JSON-строка, содержащая данные, относящиеся к операции. В целом можно писать все что угодно
- Дата и время внесения изменений (DateTimeImmutable): Дата и время, указывающее, когда была выполнена операция.
- ID пользователя (int): ID пользователя, выполнившего операцию. Если пользователь не найден, значение по умолчанию — 0.

#### Типы операций

Класс OperationTypeEnum предоставляет предопределенные константы для типов операций, которые можно зарегистрировать:

- CREATE
- UPDATE
- DELETE
- UNDEFINED

## Получение истории

Ниже приведен пример использования модуля Resolution\Changelog для записи события в журнал изменений по каналу MySQL:

```php
<?php
declare(strict_types=1);
use Bitrix\Main\Engine\CurrentUser;
use Resolution\Changelog\Changelog;
use Resolution\Changelog\Channels\MySQL\WriteChannel;
use Resolution\Changelog\Event;
use Resolution\Changelog\OperationTypeEnum;
$event = new Event(
    1,
    'someEntity',
    OperationTypeEnum::CREATE,
    json_encode(['someField' => 'someValue'], JSON_THROW_ON_ERROR),
    new DateTimeImmutable(),
    (int)CurrentUser::get()->getId()
);
$result = (new Changelog([new WriteChannel()]))
    ->sendEvent($event)
    ->getResult();
echo (int)$result->isSuccess();
```

## Каналы

Модуль поддерживает различные каналы для записи и чтения изменений. В примере выше используется канал MySQL, но вы
можете расширить функциональность, реализовав собственные каналы через интерфейсы *
**Resolution\Changelog\Channels\WriteChannelInterface** и
**Resolution\Changelog\Channels\ReadChannelInterface**