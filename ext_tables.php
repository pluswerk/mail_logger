<?php

defined('TYPO3') or die();

// Register a Backend Module
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
    'Pluswerk.mail_logger',
    'tools',     // Make module a submodule of 'tools'
    'iocenter',    // Submodule key
    '',    // Position
    [
        \Pluswerk\MailLogger\Controller\MailLogController::class => 'dashboard,show',
    ],
    [
        'access' => 'user,group',
        'icon' => 'EXT:mail_logger/Resources/Public/Icons/Extension.svg',
        'labels' => 'LLL:EXT:mail_logger/Resources/Private/Language/locallang_db.xlf',
    ]
);

// Register icons
Pluswerk\MailLogger\Utility\BackendConfigurationUtility::registerIcons();
