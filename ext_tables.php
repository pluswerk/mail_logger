<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use Pluswerk\MailLogger\Controller\MailLogController;

defined('TYPO3') || die();

// TODO can be removed if TYPO3 11 is not supported anymore:
ExtensionUtility::registerModule(
    'mail_logger',
    'tools',     // Make module a submodule of 'tools'
    'iocenter',    // Submodule key
    '',    // Position
    [
        MailLogController::class => 'dashboard,show',
    ],
    [
        'access' => 'user,group',
        'icon' => 'EXT:mail_logger/Resources/Public/Icons/Extension.svg',
        'labels' => 'LLL:EXT:mail_logger/Resources/Private/Language/locallang_db.xlf',
    ]
);
