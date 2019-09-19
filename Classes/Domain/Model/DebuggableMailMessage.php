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
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

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
        $currentTypo3Version = VersionNumberUtility::convertVersionNumberToInteger(VersionNumberUtility::getCurrentTypo3Version());
        if ($currentTypo3Version > 9000000) {
            $this->signMail();
        } else {
            $this->signMailForLegacyVersion();
        }

        $this->modifyMailForDebug();
        return parent::send();
    }

    private function signMail()
    {
        $conf = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get('mail_logger');
        if ($conf['private_key'] !== '' && $conf['domain_name'] !== '' && $conf['selector'] !== '') {
            $signer = new \Swift_Signers_DKIMSigner(
                $conf['private_key'],
                $conf['domain_name'],
                $conf['selector']
            );
            $signer->ignoreHeader('Return-Path');
            $this->attachSigner($signer);
        }
    }

    private function signMailForLegacyVersion()
    {
        $configurationUtility = GeneralUtility::makeInstance(ObjectManager::class)->get(
            \TYPO3\CMS\Extensionmanager\Utility\ConfigurationUtility::class
        );
        $conf = $configurationUtility->getCurrentConfiguration('mail_logger');
        if ($conf['private_key']['value'] !== '' && $conf['domain_name']['value'] !== '' && $conf['selector']['value'] !== '') {
            $signer = new \Swift_Signers_DKIMSigner(
                $conf['private_key']['value'],
                $conf['domain_name']['value'],
                $conf['selector']['value']
            );
            $signer->ignoreHeader('Return-Path');
            $this->attachSigner($signer);
        }
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
