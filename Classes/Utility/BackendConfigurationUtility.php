<?php

/***
 *
 * This file is part of an "+Pluswerk AG" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2018 Markus Hölzle <markus.hoelzle@pluswerk.ag>, +Pluswerk AG
 *
 ***/

declare(strict_types=1);

namespace Pluswerk\MailLogger\Utility;

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 */
class BackendConfigurationUtility
{
    /**
     * @var array
     */
    protected static $icons = [
        'mailTemplateContainer' => [
            'identifier' => 'apps-pagetree-folder-contains-mail-templates',
            'iconProviderClassName' => SvgIconProvider::class,
            'options' => ['source' => 'EXT:mail_logger/Resources/Public/Icons/MailTemplate.svg'],
        ],
    ];

    /**
     * @return void
     */
    public static function registerIcons(): void
    {
        /* @var IconRegistry $iconRegistry */
        $iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);
        foreach (static::$icons as $icon) {
            $iconRegistry->registerIcon($icon['identifier'], $icon['iconProviderClassName'], $icon['options']);
        }
    }

    public static function registerContainerFolders(array &$pagesTca): void
    {
        // add mail_logger option group
        $pagesTca['columns']['module']['config']['items'][] = [
            'LLL:EXT:mail_logger/Resources/Private/Language/locallang_db.xlf:container_title',
            '--div--',
        ];

        // add folder container for mail templates
        $pagesTca['columns']['module']['config']['items'][] = [
            'LLL:EXT:mail_logger/Resources/Private/Language/locallang_db.xlf:container_mail_templates',
            'io_mails', // have to be a very short value
            'EXT:mail_logger/Resources/Public/Icons/MailTemplate.svg',
        ];
        $pagesTca['ctrl']['typeicon_classes']['contains-' . 'io_mails'] = static::$icons['mailTemplateContainer']['identifier'];

        // add placeholder option group
        $pagesTca['columns']['module']['config']['items'][] = [
            '---',
            '--div--',
        ];
    }
}
