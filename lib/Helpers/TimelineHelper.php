<?php

declare(strict_types=1);


namespace Resolution\Changelog\Helpers;

use Bitrix\Main\Config\Configuration;
use Resolution\Changelog\Timeline;
use Throwable;

class TimelineHelper
{
    private const string MODULE_ID = 'resolution.changelog';
    private const string CHANNELS_CONFIG_NAME = 'read-channel';

    /**
     * Создает экземпляр объекта "Хронология"
     * @return Timeline|null
     */
    public static function create(): Timeline|null
    {
        try {
            return self::createInternal();
        } catch (Throwable) {
            return null;
        }
    }

    private static function createInternal(): Timeline
    {
        return new Timeline(Configuration::getInstance(self::MODULE_ID)->get(self::CHANNELS_CONFIG_NAME));
    }
}