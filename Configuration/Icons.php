<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

return [
    'mail_logger' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:mail_logger/Resources/Public/Icons/Extension.svg',
    ],
    'apps-pagetree-folder-contains-mail-templates' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:mail_logger/Resources/Public/Icons/MailTemplate.svg',
    ],
];
