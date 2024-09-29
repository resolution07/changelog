<?php

declare(strict_types=1);


namespace Resolution\Changelog\Channels\MySQL;

use Bitrix\Main\Type\DateTime;
use Exception;
use Resolution\Changelog\Channels\WriteChannelInterface;
use Resolution\Changelog\Event;
use Resolution\Changelog\Exceptions\SendEventException;
use Resolution\Changelog\Tables\ChangelogTable;

class WriteChannel implements WriteChannelInterface
{
    public function send(Event $event): true
    {
        try {
            $this->sendInternal($event);
        } catch (Exception $e) {
            throw new SendEventException($e->getMessage(), $e->getCode(), $e);
        }
        return true;
    }

    /**
     * @throws Exception
     */
    private function sendInternal(Event $event): void
    {
        ChangelogTable::add([
            'ENTITY_ID' => $event->getEntityId(),
            'ENTITY_NAME' => $event->getEntityName(),
            'OPERATION_TYPE' => $event->getOperationType()->value,
            'CHANGES' => $event->getChanges(),
            'EVENT_DATE_TIME' => DateTime::createFromTimestamp($event->getDateTime()->getTimestamp()),
            'CREATED_BY' => $event->getCreatedBy()
        ]);
    }
}