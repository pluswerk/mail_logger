<?php

defined('TYPO3') or die();

(function () {
    // Add container for mail templates
    Pluswerk\MailLogger\Utility\BackendConfigurationUtility::registerContainerFolders($GLOBALS['TCA']['pages']);
})();
