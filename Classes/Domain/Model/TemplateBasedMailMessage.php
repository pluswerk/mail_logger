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

namespace Pluswerk\MailLogger\Domain\Model;

use Pluswerk\MailLogger\Utility\ConfigurationUtility;
use TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 */
class TemplateBasedMailMessage extends LoggableMailMessage
{

    /**
     * @var \Pluswerk\MailLogger\Domain\Model\MailTemplate
     */
    protected $mailTemplate;

    /**
     * @var \TYPO3\CMS\Fluid\View\StandaloneView
     * @inject
     */
    protected $messageView;

    /**
     * @var \TYPO3\CMS\Fluid\View\StandaloneView
     * @inject
     */
    protected $subjectView;

    /**
     * @var array
     */
    protected $viewParameters = [];

    /**
     * @return MailTemplate
     */
    public function getMailTemplate()
    {
        return $this->mailTemplate;
    }

    /**
     * @param MailTemplate $mailTemplate
     * @param boolean $assignMailTemplate
     * @param array $viewParameters This is necessary if you use Fluid for your mail fields
     * @return $this
     */
    public function setMailTemplate($mailTemplate, $assignMailTemplate = true, array $viewParameters = [])
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

    /**
     * @return \TYPO3\CMS\Fluid\View\StandaloneView
     * @deprecated Please use getMessageView()
     */
    public function getView()
    {
        return $this->getMessageView();
    }

    /**
     * @param \TYPO3\CMS\Fluid\View\StandaloneView $bodyView
     * @return $this
     * @deprecated Please use setMessageView()
     */
    public function setView($bodyView)
    {
        return $this->setMessageView($bodyView);
    }

    /**
     * @return \TYPO3\CMS\Fluid\View\StandaloneView
     */
    public function getMessageView()
    {
        return $this->messageView;
    }

    /**
     * @param \TYPO3\CMS\Fluid\View\StandaloneView $messageView
     * @return $this
     */
    public function setMessageView($messageView)
    {
        $this->messageView = $messageView;
        return $this;
    }

    /**
     * @return \TYPO3\CMS\Fluid\View\StandaloneView
     */
    public function getSubjectView()
    {
        return $this->subjectView;
    }

    /**
     * @param \TYPO3\CMS\Fluid\View\StandaloneView $subjectView
     * @return $this
     */
    public function setSubjectView($subjectView)
    {
        $this->subjectView = $subjectView;
        return $this;
    }

    /**
     * @return array
     */
    public function getViewParameters()
    {
        return $this->viewParameters;
    }

    /**
     * @param array $viewParameters
     * @return $this
     */
    public function setViewParameters($viewParameters)
    {
        $this->viewParameters = $viewParameters;
        return $this;
    }

    /**
     * @return void
     */
    public function assignMailTemplate()
    {
        $this->assignMailTemplateValues($this->getMailTemplate()->_getProperties());
    }

    /**
     * @param string $typoScriptKey
     */
    public function assignDefaultsFromTypoScript(string $typoScriptKey, string $templatePathKey = 'default')
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

    /**
     * send mail
     *
     * @return int the number of recipients who were accepted for delivery
     * @throws \Exception
     */
    public function send()
    {
        try {
            $body = $this->renderView($this->messageView);
            $this->setBody($body, 'text/html');
        } catch (\Exception $e) {
            throw new \Exception('Error while setting mail body template: ' . $e->getMessage(), 1449133006, $e);
        }

        try {
            $this->setSubject($this->renderView($this->subjectView));
        } catch (\Exception $e) {
            throw new \Exception('Error while setting mail subject template: ' . $e->getMessage(), 1449133007, $e);
        }

        $this->signMail();
        return parent::send();
    }

    private function signMail()
    {
        $settings = ConfigurationUtility::getCurrentModuleConfiguration('settings');
        if (isset($settings['dkim']) && isset($settings['dkim'][$this->mailTemplate->getDkimKey()])) {
            $conf = $settings['dkim'][$this->mailTemplate->getDkimKey()];
            $signer = new \Swift_Signers_DKIMSigner(
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

    /**
     * @param array $values
     */
    protected function assignMailTemplateValues($values)
    {
        if (!empty($values['typoScriptKey'])) {
            $this->assignDefaultsFromTypoScript($values['typoScriptKey'], $this->getMailTemplate()->getTemplatePathKey());
        }

        // set From
        $fromAddress = $this->getRenderedValue($values['mailFromAddress']);
        if (!empty($fromAddress)) {
            $fromName = $this->getRenderedValue($values['mailFromName']);
            $fromName = $fromName ?: $fromAddress;
            $this->setFrom($this->cleanUpMailAddressesAndNames([$fromAddress => $fromName]));
        }

        // set To
        $toAddresses = GeneralUtility::trimExplode(',', $this->getRenderedValue($values['mailToAddresses']), true);
        if (!empty($toAddresses)) {
            $toNames = GeneralUtility::trimExplode(',', $this->getRenderedValue($values['mailToNames']));
            $combinedTo = [];
            foreach ($toAddresses as $key => $toAddress) {
                $combinedTo[$toAddress] = empty($toNames[$key]) ? '' : $toNames[$key];
            }
            $this->setTo($this->cleanUpMailAddressesAndNames($combinedTo));
        }

        // set CC and BCC
        if (!empty($values['mailCopyAddresses'])) {
            $this->setCc(GeneralUtility::trimExplode(',', $this->getRenderedValue($values['mailCopyAddresses']), true));
        }
        if (!empty($values['mailBlindCopyAddresses'])) {
            $this->setBcc(GeneralUtility::trimExplode(',', $this->getRenderedValue($values['mailBlindCopyAddresses']), true));
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

    /**
     * @param array $addressesAndNames
     * @return array
     */
    protected function cleanUpMailAddressesAndNames(array $addressesAndNames)
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
     *
     * @param string $value
     * @return string
     */
    protected function getRenderedValue($value)
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
     *
     * @param ViewInterface $view
     * @return string
     */
    protected function renderView(ViewInterface $view)
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
