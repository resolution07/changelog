<?php

declare(strict_types=1);


namespace Resolution\Changelog;

use Bitrix\Main\Error;
use Bitrix\Main\Result;
use Exception;
use Resolution\Changelog\Channels\ChannelInterface;
use Resolution\Changelog\Exceptions\MaximumChannelException;

final class History
{
    public const int MAX_CHANNEL_COUNT = 20;

    private array $channels;
    private Result $result;

    /**
     * @param array<ChannelInterface> $channels каналы истории
     * @throws MaximumChannelException если превышено допустимое количество каналов
     */
    public function __construct(array $channels)
    {
        $this->setupChannels($channels);
        $this->result = new Result();
    }

    /**
     * Добавляет новый канал отправки событий истории
     * @param ChannelInterface $channel
     * @return $this
     * @throws MaximumChannelException если будет превышено допустимое количество каналов
     */
    public function addChannel(ChannelInterface $channel): History
    {
        if (count($this->channels) === self::MAX_CHANNEL_COUNT) {
            throw new MaximumChannelException();
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
     * Отправляет переданное событие по каналам истории
     * @param Event $event
     * @return $this
     */
    public function send(Event $event): History
    {
        foreach ($this->channels as $channel) {
            $this->sendEventByChannel($event, $channel);
        }

        return $this;
    }

    private function sendEventByChannel(Event $event, ChannelInterface $channel): void
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
     * @throws MaximumChannelException
     */
    private function setupChannels(array $channels): void
    {
        if (count($channels) > self::MAX_CHANNEL_COUNT) {
            throw new MaximumChannelException();
        }

        $this->channels = $channels;
    }
}