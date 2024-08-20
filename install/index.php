<?php

declare(strict_types=1);

use Bitrix\Main\Application;
use Bitrix\Main\DB\SqlQueryException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Resolution\Changelog\Tables\ChangelogTable;

class resolution_changelog extends CModule
{
    public $MODULE_ID = 'resolution.changelog';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $PARTNER_NAME;
    public $PARTNER_URI;
    public static array $entities = [
        [
            'class' => ChangelogTable::class,
            'indexes' => [
                [
                    'name' => 'changelog_entity_name_idx',
                    'fields' => ['ENTITY_NAME']
                ],
                [
                    'name' => 'changelog_entity_id_idx',
                    'fields' => ['ENTITY_ID']
                ]
            ]
        ],
    ];

    public function __construct()
    {
        $arModuleVersion = [];

        include(__DIR__ . '/version.php');

        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        $this->MODULE_NAME = Loc::getMessage('RESOLUTION_CHANGELOG_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('RESOLUTION_CHANGELOG_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('RESOLUTION_CHANGELOG_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('RESOLUTION_CHANGELOG_PARTNER_URI');
    }

    /**
     * @throws SqlQueryException
     * @throws LoaderException
     */
    public function DoInstall(): void
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $this->installDB();
    }

    /**
     * @return void
     * @throws SqlQueryException
     * @throws LoaderException
     */
    public function installDB(): void
    {
        if (Loader::includeModule($this->MODULE_ID)) {
            $connection = Application::getInstance()->getConnection();

            foreach (self::$entities as $entity) {
                if (class_exists($entity['class']) && !$connection->isTableExists($entity['class']::getTableName())) {
                    $entity['class']::getEntity()->createDbTable();

                    if (!empty($entity['indexes'])) {
                        foreach ($entity['indexes'] as $index) {
                            $connection->createIndex(
                                $entity['class']::getTableName(),
                                $index['name'],
                                $index['fields']
                            );
                        }
                    }
                }
            }
        } else {
            throw new LoaderException('Module ' . $this->MODULE_ID . ' is not found');
        }
    }

    /**
     * @throws SqlQueryException
     * @throws LoaderException
     */
    public function DoUninstall(): void
    {
        $this->uninstallDB();
        $this->unInstallFiles();

        ModuleManager::unregisterModule($this->MODULE_ID);
    }

    /**
     * @return void
     * @throws SqlQueryException
     * @throws LoaderException
     */
    public function uninstallDB(): void
    {
        if (Loader::includeModule($this->MODULE_ID)) {
            $connection = Application::getInstance()->getConnection();

            foreach (self::$entities as $entity) {
                if (class_exists($entity['class']) && $connection->isTableExists($entity['class']::getTableName())) {
                    $connection->dropTable($entity['class']::getTableName());
                }
            }
        } else {
            throw new LoaderException('Module ' . $this->MODULE_ID . ' is not found');
        }
    }
}