# Модуль Changelog

Модуль **Changelog** предоставляет API отслеживания изменений ваших сущностей и ведения
их журнала.

## Установка

Установите модуль через Composer:

```bash
composer require resolution07/changelog
```

## Использование

Ниже приведен пример использования модуля Resolution\Changelog для записи события в журнал изменений о каналу MySQL:

```php
<?php

use Resolution\Changelog\Event;
use Resolution\Changelog\History;
use Resolution\Changelog\OperationTypeEnum;
use Resolution\Changelog\Channels\MySQL\Channel;
use Bitrix\Main\Engine\CurrentUser;

// Создание нового события
$event = new Event(
    1,                                        // ID сущности
    'test_entity',                            // Тип сущности
    OperationTypeEnum::CREATE,                // Тип операции (CREATE, UPDATE, DELETE и т.д.)
    json_encode(['test' => 'test']),          // Данные для записи (в формате JSON)
    time(),                                   // Временная метка операции
    (int)CurrentUser::get()->getId() ?? 0     // ID пользователя, выполняющего операцию
);

// Отправка события в канал истории (в данном случае MySQL)
$result = (new History([new Channel()]))->send($event)->getResult();

// Вывод результата операции
echo (int)$result->isSuccess();
```

## Параметры

- ID сущности (int): Уникальный идентификатор сущности, которая регистрируется.
- Тип сущности (string): Строковый идентификатор типа сущности (например, test_entity).
- Тип операции (OperationTypeEnum): Тип выполненной операции (CREATE, UPDATE, DELETE).
- Данные (string): JSON-строка, содержащая данные, относящиеся к операции.
- Временная метка (int): Unix-время, указывающее, когда была выполнена операция.
- ID пользователя (int): ID пользователя, выполнившего операцию. Если пользователь не найден, значение по умолчанию — 0.

## Типы операций

Класс OperationTypeEnum предоставляет предопределенные константы для типов операций, которые можно зарегистрировать:

- CREATE
- UPDATE
- DELETE

## Каналы

Модуль поддерживает различные каналы для хранения журнала изменений. В примере выше используется канал MySQL, но вы
можете расширить функциональность, реализовав собственные каналы через интерфейс
**Resolution\Changelog\Channels\ChannelInterface**.