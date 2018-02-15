<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

use Pluswerk\MailLogger\Utility\BackendConfigurationUtility;
use Pluswerk\MailLogger\Utility\ConfigurationUtility;

if (TYPO3_MODE === 'BE') {
    // Register a Backend Module
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'Pluswerk.' . ConfigurationUtility::EXTENSION_KEY,
        'tools',     // Make module a submodule of 'tools'
        'iocenter',    // Submodule key
        '',    // Position
        [
            'MailLog' => 'dashboard,show',
        ],
        [
            'access' => 'user,group',
            'icon' => 'EXT:' . ConfigurationUtility::EXTENSION_KEY . '/ext_icon.svg',
            'labels' => 'LLL:EXT:' . ConfigurationUtility::EXTENSION_KEY . '/Resources/Private/Language/locallang_db.xlf',
        ]
    );
}

// Add TypoScript
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    ConfigurationUtility::EXTENSION_KEY,
    'Configuration/TypoScript',
    '+Pluswerk AG: Mail Log'
);

// Register icons
BackendConfigurationUtility::registerIcons();

// Add container for mail templates
BackendConfigurationUtility::registerContainerFolders($GLOBALS['TCA']['pages']);
