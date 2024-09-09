<?php

declare(strict_types=1);


namespace Resolution\Changelog\Controllers;

use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Engine\Response\DataType\Page;
use Bitrix\Main\Error;
use Bitrix\Main\UI\PageNavigation;
use Resolution\Changelog\Exceptions\EventReadException;
use Resolution\Changelog\Helpers\TimelineHelper;
use Throwable;

class HistoryController extends Controller
{
    private const string PAGE_ID = 'timeline';

    public function listAction(string $entityName, PageNavigation $pageNavigation): Page
    {
        try {
            return $this->listActionInternal($entityName, $pageNavigation);
        } catch (Throwable $e) {
            $this->addError(new Error($e->getMessage()));
            return new Page(
                self::PAGE_ID,
                [],
                0
            );
        }
    }

    /**
     * @param string $entityName
     * @param PageNavigation $pageNavigation
     * @return Page
     * @throws EventReadException
     */
    private function listActionInternal(string $entityName, PageNavigation $pageNavigation): Page
    {
        $timelinePage = TimelineHelper::create()?->getPage(
            $entityName,
            $pageNavigation->getOffset(),
            $pageNavigation->getLimit()
        );

        return new Page(
            self::PAGE_ID,
            $timelinePage?->getEvents() ?? [],
            $timelinePage?->getTotalCount() ?? 0
        );
    }
}