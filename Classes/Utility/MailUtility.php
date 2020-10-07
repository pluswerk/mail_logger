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

namespace Pluswerk\MailLogger\Utility;

use Exception;
use Pluswerk\MailLogger\Domain\Model\MailTemplate;
use Pluswerk\MailLogger\Domain\Model\TemplateBasedMailMessage;
use Pluswerk\MailLogger\Domain\Repository\MailTemplateRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 */
class MailUtility
{

    /**
     * Shortcut to send mails
     * \Pluswerk\MailLogger\Utility\MailUtility::getMailByKey('exampleReport', null, ['var' => $var])->send();
     *
     * @param string $key The TypoScript key of your template
     * @param int|null $languageUid The language uid
     * @param array $viewParameters This is necessary if you use Fluid for your mail fields
     * @return \Pluswerk\MailLogger\Domain\Model\TemplateBasedMailMessage
     * @throws \Exception
     */
    public static function getMailByKey(string $key, int $languageUid = null, array $viewParameters = []): TemplateBasedMailMessage
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $mail = $objectManager->get(TemplateBasedMailMessage::class);
        $templateRepository = $objectManager->get(MailTemplateRepository::class);
        $mailTemplate = $templateRepository->findOneByTypoScriptKeyAndLanguage($key, $languageUid);
        if (!$mailTemplate) {
            throw new Exception('No "MailTemplate" was found for key "' . $key . '". Please check your database records!');
        }
        return $mail->setMailTemplate($mailTemplate, true, $viewParameters);
    }

    /**
     * Shortcut to send mails
     * \Pluswerk\MailLogger\Utility\MailUtility::getMailById($mailTemplateId, ['var' => $var])->send();
     *
     * @param int $mailTemplateId The identifier uid of your template
     * @param array $viewParameters This is necessary if you use Fluid for your mail fields
     * @return \Pluswerk\MailLogger\Domain\Model\TemplateBasedMailMessage
     * @throws \Exception
     * @deprecated will be removed. use \Pluswerk\MailLogger\Utility\MailUtility::getMailByKey instead
     */
    public static function getMailById(int $mailTemplateId, array $viewParameters = []): TemplateBasedMailMessage
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $mail = $objectManager->get(TemplateBasedMailMessage::class);
        $templateRepository = $objectManager->get(MailTemplateRepository::class);
        /** @var MailTemplate $mailTemplate */
        $mailTemplate = $templateRepository->findByUid($mailTemplateId);
        if (!$mailTemplate) {
            throw new Exception('No "MailTemplate" was found for uid "' . $mailTemplateId . '". Please check your database records!');
        }
        return $mail->setMailTemplate($mailTemplate, true, $viewParameters);
    }
}
