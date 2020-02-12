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

namespace Pluswerk\MailLogger\ViewHelpers;

use Pluswerk\MailLogger\Utility\BackendConfigurationUtility;
use Pluswerk\MailLogger\Utility\ConfigurationUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 */
class TranslateViewHelper extends AbstractViewHelper
{
    public function initializeArguments()
    {
        $this->registerArgument('id', 'string', 'the id');
        $this->registerArgument('arguments', 'array|null', 'the arguments');
    }

    /**
     * @return string
     */
    public function render()
    {
        $prefix = 'LLL:EXT:' . ConfigurationUtility::EXTENSION_KEY . BackendConfigurationUtility::LOCAL_LANGUAGE_FILE_PATH . 'tx_maillogger.';
        return LocalizationUtility::translate($prefix . $this->arguments['id'], 'MailLogger', $this->arguments['arguments']);
    }
}
