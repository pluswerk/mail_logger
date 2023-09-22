<?php

declare(strict_types=1);

namespace Pluswerk\MailLogger\ViewHelpers;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class SysLanguageUidViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('uid', 'int', 'get sys_language.title by sys_language.uid');
    }

    public function render(): string
    {
        $uid = (int)$this->arguments['uid'];
        return match ($uid) {
            0 => 'Default',
            -1 => 'All',
            default => $this->getSysLanguageRecord($uid),
        };
    }

    private function getSysLanguageRecord(int $uid): string
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_language');
        $queryBuilder->select('title')->from('sys_language')->where($queryBuilder->expr()->eq('uid', $uid));
        return $queryBuilder->executeQuery()->fetchOne() ?: (string)$uid;
    }
}
