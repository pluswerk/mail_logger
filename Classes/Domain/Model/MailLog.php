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

/**
 * Class MailLog
 */
class MailLog extends AbstractModel
{

    /**
     * @var string
     */
    protected $typoScriptKey;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $message;

    /**
     * json encoded
     *
     * @var string
     */
    protected $mailFrom;

    /**
     * json encoded
     *
     * @var string
     */
    protected $mailTo;

    /**
     * json encoded
     *
     * @var string
     */
    protected $mailCopy;

    /**
     * json encoded
     *
     * @var string
     */
    protected $mailBlindCopy;

    /**
     * @var string
     */
    protected $headers;

    /**
     * @var string
     */
    protected $result = 'Not send until now';

    /**
     * @var int
     */
    protected $sysLanguageUid;

    /**
     * @return string
     */
    public function getTypoScriptKey(): string {
        return $this->typoScriptKey;
    }

    /**
     * @param string $typoScriptKey
     * @return self
     */
    public function setTypoScriptKey($typoScriptKey) {
        $this->typoScriptKey = $typoScriptKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubject(): string {
        return $this->subject;
    }

    /**
     * @param string $subject
     * @return self
     */
    public function setSubject($subject) {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string {
        return $this->message;
    }

    /**
     * @param string $message
     * @return self
     */
    public function setMessage($message) {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getMailFrom(): string {
        return $this->mailFrom;
    }

    /**
     * @param string $mailFrom
     * @return self
     */
    public function setMailFrom($mailFrom) {
        $this->mailFrom = $mailFrom;
        return $this;
    }

    /**
     * @return string
     */
    public function getMailTo(): string {
        return $this->mailTo;
    }

    /**
     * @param string $mailTo
     * @return self
     */
    public function setMailTo($mailTo) {
        $this->mailTo = $mailTo;
        return $this;
    }

    /**
     * @return string
     */
    public function getMailCopy(): string {
        return $this->mailCopy;
    }

    /**
     * @param string $mailCopy
     * @return self
     */
    public function setMailCopy($mailCopy) {
        $this->mailCopy = $mailCopy;
        return $this;
    }

    /**
     * @return string
     */
    public function getMailBlindCopy(): string {
        return $this->mailBlindCopy;
    }

    /**
     * @param string $mailBlindCopy
     * @return self
     */
    public function setMailBlindCopy($mailBlindCopy) {
        $this->mailBlindCopy = $mailBlindCopy;
        return $this;
    }

    /**
     * @return string
     */
    public function getHeaders(): string {
        return $this->headers;
    }

    /**
     * @param string $headers
     * @return self
     */
    public function setHeaders($headers) {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @return string
     */
    public function getResult(): string {
        return $this->result;
    }

    /**
     * @param string $result
     * @return self
     */
    public function setResult($result) {
        $this->result = $result;
        return $this;
    }

    /**
     * @return int
     */
    public function getSysLanguageUid(): int {
        return $this->sysLanguageUid;
    }

    /**
     * @param int $sysLanguageUid
     * @return self
     */
    public function setSysLanguageUid($sysLanguageUid) {
        $this->sysLanguageUid = $sysLanguageUid;
        return $this;
    }

}
