<?php

declare(strict_types=1);


namespace Resolution\Changelog\Helpers;

use Bitrix\Main\Config\Configuration;
use Resolution\Changelog\Exceptions\MaximumChannelCountException;
use Resolution\Changelog\Changelog;
use Throwable;

class ChangelogHelper
{
    private const string MODULE_ID = 'resolution.changelog';
    private const string CHANNELS_CONFIG_NAME = 'write-channels';

    /**
     * Создает экземпляр объекта "История"
     * @return Changelog|null
     */
    public static function create(): Changelog|null
    {
        try {
            return self::createInternal();
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * @throws MaximumChannelCountException
     */
    private static function createInternal(): Changelog
    {
        return new Changelog(Configuration::getInstance(self::MODULE_ID)->get(self::CHANNELS_CONFIG_NAME));
    }
}