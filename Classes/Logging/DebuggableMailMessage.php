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

namespace Pluswerk\MailLogger\Domain\Model;

use Pluswerk\MailLogger\Utility\ConfigurationUtility;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use function in_array;

/**
 */
class DebuggableMailMessage extends MailMessage
{
    /**
     * @var bool
     */
    protected $debug = false;

    public function send(): bool
    {
        $this->modifyMailForDebug();
        return parent::send();
    }

    public function setDebug(bool $debug): self
    {
        $this->debug = $debug;
        return $this;
    }

    protected function modifyMailForDebug(): void
    {
        $settings = ConfigurationUtility::getCurrentModuleConfiguration('settings');
        if (
            $this->debug ||
            (
                !empty($settings['debug']['mail']['enable']) &&
                $settings['debug']['mail']['enable'] &&
                (
                    $settings['debug']['mail']['ip'] === '*' ||
                    in_array($_SERVER['REMOTE_ADDR'], GeneralUtility::trimExplode(',', $settings['debug']['mail']['ip'], true), true)
                )
            )
        ) {
            $nL = '<br/>' . "\n";
            $messageSuffix = $nL . $nL . $nL . '----------' . $nL .
                'To:' . $nL . nl2br(var_export($this->getTo(), true)) . $nL .
                'CC:' . $nL . nl2br(var_export($this->getCc(), true)) . $nL .
                'BCC:' . $nL . nl2br(var_export($this->getBcc(), true));
            $this->setTo(GeneralUtility::trimExplode(',', $settings['debug']['mail']['mailRedirect'], true));
            $this->setCc([]);
            $this->setBcc([]);
            $this->text($this->getFullBodyDebug() . str_replace('  ', '&nbsp;&nbsp;', $messageSuffix));
            $this->html(null);
        }
    }

    protected function getFullBodyDebug(): string
    {
        return implode('<br><br><br><br>', array_filter([$this->getTextBody(), $this->getHtmlBody()]));
    }
}
