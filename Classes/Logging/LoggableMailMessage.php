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

use Pluswerk\MailLogger\Domain\Repository\MailLogRepository;
use Symfony\Component\Mime\Address;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MailUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

/**
 */
class LoggableMailMessage extends DebuggableMailMessage
{

    /**
     * @var \Pluswerk\MailLogger\Domain\Model\MailLog
     */
    protected $mailLog;

    public function send(): bool
    {
        if (empty($this->getFrom())) {
            $this->setFrom(MailUtility::getSystemFrom());
        }
        $mailLogRepository = GeneralUtility::makeInstance(ObjectManager::class)->get(MailLogRepository::class);

        // write mail to log before send
        $this->getMailLog(); //just for init mail log
        $this->assignMailLog();
        $mailLogRepository->add($this->mailLog);
        GeneralUtility::makeInstance(PersistenceManager::class)->persistAll();

        // send mail
        $result = parent::send();

        // write result to log after send
        $this->assignMailLog();
        $this->mailLog->setResult((string)$result);
        $mailLogRepository->update($this->mailLog);
        return $result;
    }

    public function getMailLog(): MailLog
    {
        if ($this->mailLog === null) {
            $this->mailLog = GeneralUtility::makeInstance(MailLog::class);
        }
        return $this->mailLog;
    }

    protected function assignMailLog(): void
    {
        $this->mailLog->setSubject($this->getSubject());
        $this->mailLog->setMessage($this->getFullBodyDebug());
        $this->mailLog->setMailFrom($this->addressesToString($this->getFrom()));
        $this->mailLog->setMailTo($this->addressesToString($this->getTo()));
        $this->mailLog->setMailCopy($this->addressesToString($this->getCc()));
        $this->mailLog->setMailBlindCopy($this->addressesToString($this->getBcc()));
        $this->mailLog->setHeaders($this->getHeaders()->toString());
    }

    protected function addressesToString(array $addresses): string
    {
        return implode(
            ', ',
            array_map(
                function (Address $address) {
                    return $address->getName() . ' <' . $address->getAddress() . '>';
                },
                $addresses
            )
        );
    }
}
