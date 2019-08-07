<?php

namespace Pluswerk\MailLogger\Domain\Model;

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
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 */
class DebuggableMailMessage extends MailMessage
{

    /**
     * @var bool
     */
    protected $debug = false;

    /**
     * send mail
     *
     * @return int the number of recipients who were accepted for delivery
     */
    public function send()
    {
        $conf = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('mail_logger');
        if ($conf['private_key'] !== '' && $conf['domain_name'] !== '' && $conf['selector'] !== '') {
            $signer = new \Swift_Signers_DKIMSigner(
                $conf['private_key'],
                $conf['domain_name'],
                $conf['selector']
            );
            $signer->ignoreHeader('Return-Path');
            $this->attachSigner($signer);
        }

        $this->modifyMailForDebug();
        return parent::send();
    }

    /**
     * @param bool $debug
     * @return $this
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
        return $this;
    }

    /**
     * @return void
     */
    protected function modifyMailForDebug()
    {
        $settings = ConfigurationUtility::getCurrentModuleConfiguration('settings');
        if ($this->debug ||
            (
                !empty($settings['debug']['mail']['enable']) &&
                $settings['debug']['mail']['enable'] &&
                (
                    $settings['debug']['mail']['ip'] === '*' ||
                    \in_array($_SERVER['REMOTE_ADDR'], GeneralUtility::trimExplode(',', $settings['debug']['mail']['ip'], true), true)
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
            $this->setBody($this->getBody() . str_replace('  ', '&nbsp;&nbsp;', $messageSuffix));
        }
    }
}
