<?php

declare(strict_types=1);

namespace Pluswerk\MailLogger\Wizard;

use Pluswerk\MailLogger\Utility\ConfigurationUtility;
use TYPO3\CMS\Core\SingletonInterface;

class MailTemplate implements SingletonInterface
{
    /**
     * @param array{items: list<array{value: string, label: string}> } $config
     */
    public function getTypoScriptKeys(array &$config): void
    {
        $items = [['', '']];
        $settings = ConfigurationUtility::getCurrentModuleConfiguration('settings');
        foreach ($settings['mailTemplates'] ?? [] as $key => $value) {
            $items[] = [$value['label'] ?: $key, $key];
        }

        $config['items'] = array_merge($config['items'], $items);
    }

    /**
     * @param array{items: list<array{value: string, label: string}> } $config
     */
    public function getDkimKeys(array &$config): void
    {
        $items = [['', '']];
        $settings = ConfigurationUtility::getCurrentModuleConfiguration('settings');
        foreach ($settings['dkim'] ?? [] as $key => $value) {
            $items[] = [$value['domain'] ?: $key, $key];
        }

        $config['items'] = array_merge($config['items'], $items);
    }

    /**
     * @param array{items: list<array{value: string, label: string}> } $config
     */
    public function getTemplatePathKeys(array &$config): void
    {
        $items = [];
        $settings = ConfigurationUtility::getCurrentModuleConfiguration('settings');
        if (!empty($settings['templateOverrides'])) {
            foreach ($settings['templateOverrides'] as $key => $value) {
                $items[] = [$value['title'] ?: $key, $key];
            }
        }

        $config['items'] = [...$config['items'], ...$items];
    }
}
