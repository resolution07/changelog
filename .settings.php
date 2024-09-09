<?php

declare(strict_types=1);

return [
    'controllers' => [
        'value' => [
            'defaultNamespace' => '\\Resolution\\Changelog\\Controllers\\',
        ],
        'readonly' => true
    ],
    /**
     * Список каналов по умолчанию.
     * Будут использованы в случае использования HistoryHelper.
     */
    'write-channels' => [
        'value' => [
            new Resolution\Changelog\Channels\MySQL\WriteChannel()
        ],
        'readonly' => true
    ],
    /**
     * Список каналов по умолчанию.
     * Будут использованы в случае использования HistoryHelper.
     */
    'read-channel' => [
        'value' => new Resolution\Changelog\Channels\MySQL\ReadChannel(),
        'readonly' => true
    ]
];