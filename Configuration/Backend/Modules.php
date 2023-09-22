<?php

declare(strict_types=1);

use Pluswerk\MailLogger\Controller\MailLogController;

return [
    'admin_examples' => [
        'parent' => 'tools',
        'position' => [],
        'access' => 'user',
        'workspaces' => 'live',
        'path' => '/module/tools/mail-logger',
        'labels' => 'LLL:EXT:mail_logger/Resources/Private/Language/locallang_db.xlf',
        'extensionName' => 'MailLogger',
        'iconIdentifier' => 'mail_logger',
        'controllerActions' => [
            MailLogController::class => [
                'dashboard','show',
            ],
        ],
    ],
];
