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

use Exception;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\BackendConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 */
class ConfigurationUtility
{
    /**
     * @var array
     */
    protected static $currentModuleConfiguration = [];

    /**
     * @param string $key
     * @return array
     */
    public static function getCurrentModuleConfiguration(string $key): array
    {
        if (empty(self::$currentModuleConfiguration)) {
            $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
            assert($objectManager instanceof ObjectManager);
            $configurationManager = $objectManager->get(ConfigurationManagerInterface::class);
            assert($configurationManager instanceof ConfigurationManagerInterface);
            $fullTypoScript = null;

            // fix flux bug: flux has a own BackendConfigurationManager which uses a strange root page for TS setup
            if (defined('TYPO3_MODE') && TYPO3_MODE === 'BE' && $configurationManager instanceof \FluidTYPO3\Flux\Configuration\ConfigurationManager) {
                $backendConfigurationManager = $objectManager->get(BackendConfigurationManager::class);
                assert($backendConfigurationManager instanceof BackendConfigurationManager);
                $fullTypoScript = $backendConfigurationManager->getTypoScriptSetup();
            }

            if ($fullTypoScript === null) {
                $fullTypoScript = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
            }

            $typoScriptService = $objectManager->get(TypoScriptService::class);

            if (empty($fullTypoScript['module.']['tx_maillogger.'])) {
                throw new Exception('Constants and setup TypoScript are not included!');
            }
            self::$currentModuleConfiguration = $typoScriptService->convertTypoScriptArrayToPlainArray($fullTypoScript['module.']['tx_maillogger.']);
        }
        return self::$currentModuleConfiguration[$key];
    }
}
