<?php

declare(strict_types=1);


namespace Resolution\Changelog\Channels;

use Resolution\Changelog\Event;
use Resolution\Changelog\Exceptions\SendEventException;

/** Общий интерфейс каналов отправки */
interface ChannelInterface
{
    /**
     * Отправляет переданное событие по конкретному каналу
     * @param Event $event
     * @return true
     * @throws SendEventException
     */
    public function send(Event $event): true;
}