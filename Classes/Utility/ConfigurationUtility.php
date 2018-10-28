<?php

namespace Pluswerk\MailLogger\Utility;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\BackendConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;

/**
 */
class ConfigurationUtility
{
    const EXTENSION_KEY = 'mail_logger';

    /**
     * @var array
     */
    protected static $currentModuleConfiguration = [];

    /**
     * @param string $key
     * @return array
     */
    public static function getCurrentModuleConfiguration($key)
    {
        if (empty(self::$currentModuleConfiguration)) {
            /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
            $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
            /** @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager */
            $configurationManager = $objectManager->get(ConfigurationManagerInterface::class);
            $fullTypoScript = null;

            // fix flux bug: flux has a own BackendConfigurationManager which uses a strange root page for TS setup
            // hint: use classes as string, because flux is maybe not installed
            if (defined('TYPO3_MODE') && TYPO3_MODE === 'BE' && get_class($configurationManager) === 'FluidTYPO3\Flux\Configuration\ConfigurationManager') {
                /** @var \FluidTYPO3\Flux\Configuration\ConfigurationManager $configurationManager */
                /** @var BackendConfigurationManager $backendConfigurationManager */
                $backendConfigurationManager = $objectManager->get(BackendConfigurationManager::class);
                $fullTypoScript = $backendConfigurationManager->getTypoScriptSetup();
            }

            if ($fullTypoScript === null) {
                $fullTypoScript = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
            }
            /** @var TypoScriptService $typoScriptService */
            $typoScriptService = $objectManager->get(TypoScriptService::class);
            self::$currentModuleConfiguration = $typoScriptService->convertTypoScriptArrayToPlainArray($fullTypoScript['module.']['tx_maillogger.']);
        }
        return self::$currentModuleConfiguration[$key];
    }
}
