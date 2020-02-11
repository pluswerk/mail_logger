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

use Pluswerk\MailLogger\Domain\Repository\MailLogRepository;
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

    /**
     * send mail
     *
     * @return int the number of recipients who were accepted for delivery
     */
    public function send()
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
        $this->mailLog->setResult($result);
        $mailLogRepository->update($this->mailLog);
        return $result;
    }

    /**
     * @return MailLog
     */
    public function getMailLog()
    {
        if ($this->mailLog === null) {
            $this->mailLog = GeneralUtility::makeInstance(MailLog::class);
        }
        return $this->mailLog;
    }

    /**
     * @return void
     */
    protected function assignMailLog()
    {
        $this->mailLog->setSubject($this->getSubject());
        if ($this->getBody() !== null) {
            $this->mailLog->setMessage($this->getBody());
        } else {
            $this->mailLog->setMessage($this->getBodiesOfChildren());
        }
        $this->mailLog->setMailFrom($this->getHeaders()->get('from') ? $this->getHeaders()->get('from')->getFieldBody() : '');
        $this->mailLog->setMailTo($this->getHeaders()->get('to') ? $this->getHeaders()->get('to')->getFieldBody() : '');
        $this->mailLog->setMailCopy($this->getHeaders()->get('cc') ? $this->getHeaders()->get('cc')->getFieldBody() : '');
        $this->mailLog->setMailBlindCopy($this->getHeaders()->get('bcc') ? $this->getHeaders()->get('bcc')->getFieldBody() : '');
        $this->mailLog->setHeaders($this->getHeaders()->toString());
    }

    protected function getBodiesOfChildren()
    {
        $string = '';
        if (!empty($this->getChildren())) {
            foreach ($this->getChildren() as $child) {
                $string .= $child->toString() . '<br><br><br><br>';
            }
        }
        return utf8_decode(utf8_encode(quoted_printable_decode($string)));
    }
}
