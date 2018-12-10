<?php

// XClass TYPO3 Mail Objects
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Core\Mail\MailMessage::class] = [
    'className' => \Pluswerk\MailLogger\Domain\Model\LoggableMailMessage::class,
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptConstants(
    '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:mail_logger/Configuration/TypoScript/constants.typoscript">'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
    '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:mail_logger/Configuration/TypoScript/setup.typoscript">'
);
