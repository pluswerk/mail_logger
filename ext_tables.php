<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

use Pluswerk\MailLogger\Utility\BackendConfigurationUtility;

if (TYPO3_MODE === 'BE') {
    // Register a Backend Module
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'Pluswerk.' . 'mail_logger',
        'tools',     // Make module a submodule of 'tools'
        'iocenter',    // Submodule key
        '',    // Position
        [
            'MailLog' => 'dashboard,show',
        ],
        [
            'access' => 'user,group',
            'icon' => 'EXT:' . 'mail_logger' . '/ext_icon.svg',
            'labels' => 'LLL:EXT:' . 'mail_logger' . '/Resources/Private/Language/locallang_db.xlf',
        ]
    );
}

// Add TypoScript
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'mail_logger',
    'Configuration/TypoScript',
    '+Pluswerk AG: Mail Log'
);

// Register icons
BackendConfigurationUtility::registerIcons();

// Add container for mail templates
BackendConfigurationUtility::registerContainerFolders($GLOBALS['TCA']['pages']);
