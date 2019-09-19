<?php

namespace Pluswerk\MailLogger\Wizard;

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

use Pluswerk\MailLogger\Utility\ConfigurationUtility;
use TYPO3\CMS\Core\SingletonInterface;

/**
 */
class MailTemplate implements SingletonInterface
{

    /**
     * getTypoScriptKeys
     *
     * @param array $config
     */
    public function getTypoScriptKeys(&$config)
    {
        $items = [['', '']];
        $settings = ConfigurationUtility::getCurrentModuleConfiguration('settings');
        if (!empty($settings['mailTemplates'])) {
            foreach ($settings['mailTemplates']?:[] as $key => $value) {
                $items[] = [$value['label'] ?: $key, $key];
            }
        }

        $config['items'] = array_merge($config['items'], $items);
    }

    /**
     * getDkimKeys
     *
     * @param array $config
     */
    public function getDkimKeys(&$config)
    {
        $items = [['', '']];
        $settings = ConfigurationUtility::getCurrentModuleConfiguration('settings');
        if (!empty($settings['dkim'])) {
            foreach ($settings['dkim']?:[] as $key => $value) {
                $items[] = [$value['domain'] ?: $key, $key];
            }
        }
        $config['items'] = array_merge($config['items'], $items);
    }
}
