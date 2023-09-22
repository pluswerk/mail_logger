<?php

declare(strict_types=1);

namespace Pluswerk\MailLogger\Utility;

use TYPO3\CMS\Core\Http\ApplicationType;
use Exception;
use FluidTYPO3\Flux\Configuration\ConfigurationManager as FluxConfigurationManager;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\BackendConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

class ConfigurationUtility
{
    /** @var array<array-key, mixed> */
    protected static array $currentModuleConfiguration = [];

    /**
     * @return array<array-key, mixed>
     */
    public static function getCurrentModuleConfiguration(string $key): array
    {
        if (!self::$currentModuleConfiguration) {
            $configurationManager = GeneralUtility::makeInstance(ConfigurationManagerInterface::class);
            $fullTypoScript = null;

            // fix flux bug: flux has a own BackendConfigurationManager which uses a strange root page for TS setup
            // TODO check if still needed
            if (ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isBackend() && class_exists(FluxConfigurationManager::class) && $configurationManager instanceof FluxConfigurationManager) {
                $backendConfigurationManager = GeneralUtility::makeInstance(BackendConfigurationManager::class);
                assert($backendConfigurationManager instanceof BackendConfigurationManager);
                $fullTypoScript = $backendConfigurationManager->getTypoScriptSetup();
            }

            if ($fullTypoScript === null) {
                $fullTypoScript = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
            }

            $typoScriptService = GeneralUtility::makeInstance(TypoScriptService::class);

            if (empty($fullTypoScript['module.']['tx_maillogger.'])) {
                throw new Exception('Constants and setup TypoScript are not included!');
            }

            self::$currentModuleConfiguration = $typoScriptService->convertTypoScriptArrayToPlainArray($fullTypoScript['module.']['tx_maillogger.']);
        }

        return self::$currentModuleConfiguration[$key];
    }
}
