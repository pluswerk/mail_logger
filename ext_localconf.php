<?php

defined('TYPO3') or die();

(function () {
    // XClass TYPO3 Mail Objects
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Core\Mail\MailMessage::class] = [
        'className' => \Pluswerk\MailLogger\Domain\Model\LoggableMailMessage::class,
    ];
})();
