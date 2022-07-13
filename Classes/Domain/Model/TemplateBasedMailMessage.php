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

use Exception;
use Pluswerk\MailLogger\Utility\ConfigurationUtility;
use Swift_Signers_DKIMSigner;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Fluid\View\StandaloneView;

class TemplateBasedMailMessage extends MailMessage
{
    /**
     * @var \Pluswerk\MailLogger\Domain\Model\MailTemplate
     */
    protected $mailTemplate;

    /**
     * @var \TYPO3\CMS\Fluid\View\StandaloneView
     */
    protected $messageView;

    /**
     * @var \TYPO3\CMS\Fluid\View\StandaloneView
     */
    protected $subjectView;

    /**
     * @var mixed[]
     */
    protected $viewParameters = [];

    public function injectMessageView(StandaloneView $messageView): void
    {
        $this->messageView = $messageView;
    }

    public function injectSubjectView(StandaloneView $subjectView): void
    {
        $this->subjectView = $subjectView;
    }

    public function getMailTemplate(): MailTemplate
    {
        return $this->mailTemplate;
    }

    /**
     * @param MailTemplate $mailTemplate
     * @param boolean $assignMailTemplate
     * @param array $viewParameters This is necessary if you use Fluid for your mail fields
     * @return $this
     */
    public function setMailTemplate(MailTemplate $mailTemplate, bool $assignMailTemplate = true, array $viewParameters = []): self
    {
        $this->mailTemplate = $mailTemplate;
        if (empty($viewParameters) === false) {
            $this->setViewParameters($viewParameters);
        }
        if ($assignMailTemplate) {
            $this->assignMailTemplate();
        }
        return $this;
    }

    public function getMessageView(): StandaloneView
    {
        return $this->messageView;
    }

    public function setMessageView(StandaloneView $messageView): self
    {
        $this->messageView = $messageView;
        return $this;
    }

    public function getSubjectView(): StandaloneView
    {
        return $this->subjectView;
    }

    public function setSubjectView(StandaloneView $subjectView): self
    {
        $this->subjectView = $subjectView;
        return $this;
    }

    public function getViewParameters(): array
    {
        return $this->viewParameters;
    }

    public function setViewParameters(array $viewParameters): self
    {
        $this->viewParameters = $viewParameters;
        return $this;
    }

    public function assignMailTemplate(): void
    {
        $this->assignMailTemplateValues($this->getMailTemplate()->_getProperties());
    }

    public function assignDefaultsFromTypoScript(string $typoScriptKey, string $templatePathKey = 'default'): void
    {
        if (!empty($typoScriptKey)) {
            $this->getMailLog()->setTypoScriptKey($typoScriptKey);
            $settings = ConfigurationUtility::getCurrentModuleConfiguration('settings');
            $concreteSettings = $settings['mailTemplates'][$typoScriptKey];
            $concreteSettings['templatePaths'] = $settings['templateOverrides'][$templatePathKey];
            $concreteSettings['defaultTemplatePaths'] = $settings['templateOverrides']['default'];
            $this->assignMailTemplateValues($concreteSettings);
        }
    }

    public function send(): bool
    {
        try {
            $body = $this->renderView($this->messageView);
            $this->html($body);
        } catch (Exception $e) {
            throw new Exception('Error while setting mail body template: ' . $e->getMessage(), 1449133006, $e);
        }

        try {
            $this->setSubject($this->renderView($this->subjectView));
        } catch (Exception $e) {
            throw new Exception('Error while setting mail subject template: ' . $e->getMessage(), 1449133007, $e);
        }

        $this->signMail();
        return parent::send();
    }

    private function signMail(): void
    {
        $settings = ConfigurationUtility::getCurrentModuleConfiguration('settings');
        if (isset($settings['dkim']) && isset($settings['dkim'][$this->mailTemplate->getDkimKey()])) {
            $conf = $settings['dkim'][$this->mailTemplate->getDkimKey()];
            $signer = new Swift_Signers_DKIMSigner(
                $this->formPrivateKey($conf['key']),
                $conf['domain'],
                $conf['selector']
            );
            $signer->ignoreHeader('Return-Path');
            $this->attachSigner($signer);
        }
    }


    private function formPrivateKey(string $key): string
    {
        $begin = '-----BEGIN RSA PRIVATE KEY-----';
        $ending = '-----END RSA PRIVATE KEY-----';
        return $begin . PHP_EOL . trim($key) . PHP_EOL . $ending;
    }

    protected function assignMailTemplateValues(array $values): void
    {
        if (!empty($values['typoScriptKey'])) {
            $this->assignDefaultsFromTypoScript($values['typoScriptKey'], $this->getMailTemplate()->getTemplatePathKey());
        }

        // set From
        $fromAddress = $this->getRenderedValue($values['mailFromAddress'] ?? '');
        if (!empty($fromAddress)) {
            $fromName = $this->getRenderedValue($values['mailFromName'] ?? '');
            $fromName = $fromName ?: $fromAddress;
            $this->setFrom($this->cleanUpMailAddressesAndNames([$fromAddress => $fromName]));
        }

        // set To
        $toAddresses = GeneralUtility::trimExplode(',', $this->getRenderedValue($values['mailToAddresses'] ?? ''), true);
        if (!empty($toAddresses)) {
            $toNames = GeneralUtility::trimExplode(',', $this->getRenderedValue($values['mailToNames'] ?? ''));
            $combinedTo = [];
            foreach ($toAddresses as $key => $toAddress) {
                $combinedTo[$toAddress] = empty($toNames[$key]) ? '' : $toNames[$key];
            }
            $this->setTo($this->cleanUpMailAddressesAndNames($combinedTo));
        }

        // set CC and BCC
        if (!empty($values['mailCopyAddresses'])) {
            $this->setCc(GeneralUtility::trimExplode(',', $this->getRenderedValue($values['mailCopyAddresses'] ?? ''), true));
        }
        if (!empty($values['mailBlindCopyAddresses'])) {
            $this->setBcc(GeneralUtility::trimExplode(',', $this->getRenderedValue($values['mailBlindCopyAddresses'] ?? ''), true));
        }

        $this->assignMailTemplatePaths($values);

        // set subject and message
        if (!empty($values['subject'])) {
            $this->subjectView->setTemplateSource($values['subject']);
        }
        if (!empty($values['message'])) {
            $mailView = GeneralUtility::makeInstance(StandaloneView::class);
            $mailView->setTemplateSource($values['message']);
            $mailView->assignMultiple($this->viewParameters);
            $this->messageView->assign('message', $mailView->render());
        }

        $this->messageView->assign('mailTemplate', $this->getMailTemplate());
    }

    protected function cleanUpMailAddressesAndNames(array $addressesAndNames): array
    {
        foreach ($addressesAndNames as $mailAddress => $name) {
            if (empty($name) && is_string($mailAddress)) {
                unset($addressesAndNames[$mailAddress]);
                $addressesAndNames[] = $mailAddress;
            }
        }
        return $addressesAndNames;
    }

    /**
     * Short method to render a standalone fluid template
     */
    protected function getRenderedValue(string $value): string
    {
        // Check if the string is not empty and contains any Fluid stuff
        if (empty($value) === false && (strpos($value, '{') !== false || strpos($value, '<') !== false)) {
            /** @var StandaloneView $valueView */
            $valueView = GeneralUtility::makeInstance(ObjectManager::class)->get(StandaloneView::class);
            $valueView->setTemplateSource($value);
            $value = $this->renderView($valueView);
        }
        return $value;
    }

    /**
     * Render view with all parameters
     */
    protected function renderView(ViewInterface $view): string
    {
        return $view->assignMultiple($this->getViewParameters())->render();
    }

    private function assignMailTemplatePaths(array $values): void
    {
        if (empty($this->messageView->getTemplatePathAndFilename())) {
            $this->messageView->setTemplatePathAndFilename(
                $values['templatePaths']['templatePath'] ?? $values['defaultTemplatePaths']['templatePath']
            );

            $this->messageView->setPartialRootPaths(
                array_filter(
                    [
                        $values['defaultTemplatePaths']['partialRootPaths'],
                        $values['templatePaths']['partialRootPaths'] ?? [],
                    ]
                )
            );
            $this->messageView->setLayoutRootPaths(
                array_filter(
                    [
                        $values['defaultTemplatePaths']['layoutRootPaths'],
                        $values['templatePaths']['layoutRootPaths'] ?? [],
                    ]
                )
            );

            if (isset($values['templatePaths']['settings']) && !empty($values['templatePaths']['settings'])) {
                $this->messageView->assignMultiple(['settings' => $values['templatePaths']['settings']]);
            }
        }
    }
}
