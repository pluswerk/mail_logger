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

use Pluswerk\MailLogger\Domain\Model\MailLog;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 */
class IsPlainTextMailLogViewHelper extends AbstractViewHelper
{
    public function initializeArguments()
    {
        $this->registerArgument('mailLog', MailLog::class, 'MailLog to analyze');
    }

    /**
     * @return bool
     */
    public function render()
    {
        $isPlainText = false;
        /** @var MailLog $mailLog */
        $mailLog = $this->arguments['mailLog'];
        foreach (explode("\n", $mailLog->getHeaders()) as $headerLine) {
            if (stripos($headerLine, 'Content-Type:') === 0 && stripos($headerLine, 'text/plain') !== false) {
                $isPlainText = true;
                break;
            }
        }
        return $isPlainText;
    }
}
