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

namespace Pluswerk\MailLogger\Utility;

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 */
class BackendConfigurationUtility
{
    const LOCAL_LANGUAGE_FILE_PATH = '/Resources/Private/Language/locallang_db.xlf:';


    /**
     * @var array
     */
    protected static $icons = [
        'mailTemplateContainer' => [
            'apps-pagetree-folder-contains-mail-templates',
            SvgIconProvider::class,
            ['source' => 'EXT:mail_logger/Resources/Public/Icons/MailTemplate.svg'],
        ],
    ];

    /**
     * @return void
     */
    public static function registerIcons()
    {
        /* @var IconRegistry $iconRegistry */
        $iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);
        foreach (static::$icons as &$icon) {
            $iconRegistry->registerIcon($icon[0], $icon[1], $icon[2]);
        }
    }

    /**
     * @param $pagesTca
     */
    public static function registerContainerFolders(&$pagesTca)
    {
        // add mail_logger option group
        $pagesTca['columns']['module']['config']['items'][] = [
            'LLL:EXT:' . ConfigurationUtility::EXTENSION_KEY . static::LOCAL_LANGUAGE_FILE_PATH . 'container_title',
            '--div--',
        ];

        // add folder container for mail templates
        $moduleKey = 'io_mails';
        $pagesTca['columns']['module']['config']['items'][] = [
            'LLL:EXT:' . ConfigurationUtility::EXTENSION_KEY . static::LOCAL_LANGUAGE_FILE_PATH . 'container_mail_templates',
            $moduleKey, // have to be a very short value
            'EXT:' . ConfigurationUtility::EXTENSION_KEY . '/Resources/Public/Icons/MailTemplate.svg',
        ];
        $pagesTca['ctrl']['typeicon_classes']['contains-' . $moduleKey] = static::$icons['mailTemplateContainer'][0];

        // add placeholder option group
        $pagesTca['columns']['module']['config']['items'][] = [
            '---',
            '--div--',
        ];
    }
}
