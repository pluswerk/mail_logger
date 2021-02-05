<?php
defined('TYPO3') or die();

(function () {
    // Add TypoScript
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        'mail_logger',
        'Configuration/TypoScript',
        '+Pluswerk AG: Mail Log'
    );
})();
