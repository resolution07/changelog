<?php

declare(strict_types=1);


namespace Resolution\Changelog;

use Bitrix\Main\Error;
use Bitrix\Main\Result;
use Exception;
use Resolution\Changelog\Channels\WriteChannelInterface;
use Resolution\Changelog\Exceptions\MaximumChannelCountException;

final class Changelog
{
    public const int MAX_CHANNEL_COUNT = 20;
    private array $channels;
    private Result $result;

    /**
     * @param array<WriteChannelInterface> $channels каналы истории
     * @throws MaximumChannelCountException если превышено допустимое количество каналов
     */
    public function __construct(array $channels)
    {
        $this->setupChannels($channels);
        $this->result = new Result();
    }

    /**
     * Добавляет новый канал отправки событий
     * @param WriteChannelInterface $channel
     * @return $this
     * @throws MaximumChannelCountException если будет превышено допустимое количество каналов
     */
    public function appendChannel(WriteChannelInterface $channel): Changelog
    {
        if (count($this->channels) === self::MAX_CHANNEL_COUNT) {
            throw new MaximumChannelCountException();
        }
        $this->channels[] = $channel;
        return $this;
    }

    /**
     * Возвращает результат отправки события по каналам
     * @return Result
     */
    public function getResult(): Result
    {
        return $this->result;
    }

    /**
     * Отправляет переданное событие по каналам
     * @param Event $event
     * @return $this
     */
    public function sendEvent(Event $event): Changelog
    {
        foreach ($this->channels as $channel) {
            $this->sendEventByChannel($event, $channel);
        }
        return $this;
    }

    private function sendEventByChannel(Event $event, WriteChannelInterface $channel): void
    {
        try {
            $channel->send($event);
        } catch (Exception $e) {
            $this->result->addError(new Error($e->getMessage(), $e->getCode(), $e));
        }
    }

    /**
     * @param array $channels
     * @return void
     * @throws MaximumChannelCountException
     */
    private function setupChannels(array $channels): void
    {
        if (count($channels) > self::MAX_CHANNEL_COUNT) {
            throw new MaximumChannelCountException();
        }
        $this->channels = $channels;
    }
}