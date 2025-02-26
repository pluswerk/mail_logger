<?php

declare(strict_types=1);

namespace Pluswerk\MailLogger\Utility;

use Exception;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
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

            $fullTypoScript = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);

            $typoScriptService = GeneralUtility::makeInstance(TypoScriptService::class);

            if (empty($fullTypoScript['module.']['tx_maillogger.'])) {
                throw new Exception('Constants and setup TypoScript are not included!', 7780827935);
            }

            self::$currentModuleConfiguration = $typoScriptService->convertTypoScriptArrayToPlainArray($fullTypoScript['module.']['tx_maillogger.']);
        }

        return self::$currentModuleConfiguration[$key];
    }
}
