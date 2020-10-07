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

/**
 * Class MailLog
 */
class MailLog extends AbstractModel
{
    /**
     * @var string
     */
    protected $typoScriptKey = '';

    /**
     * @var string
     */
    protected $subject = '';

    /**
     * @var string
     */
    protected $message = '';

    /**
     * json encoded
     *
     * @var string
     */
    protected $mailFrom = '';

    /**
     * json encoded
     *
     * @var string
     */
    protected $mailTo = '';

    /**
     * json encoded
     *
     * @var string
     */
    protected $mailCopy = '';

    /**
     * json encoded
     *
     * @var string
     */
    protected $mailBlindCopy = '';

    /**
     * @var string
     */
    protected $headers = '';

    /**
     * @var string
     */
    protected $result = 'Not send until now';

    /**
     * @var int
     */
    protected $sysLanguageUid = 0;

    public function getTypoScriptKey(): string
    {
        return $this->typoScriptKey;
    }

    public function setTypoScriptKey(string $typoScriptKey): self
    {
        $this->typoScriptKey = $typoScriptKey;
        return $this;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function getMailFrom(): string
    {
        return $this->mailFrom;
    }

    public function setMailFrom(string $mailFrom): self
    {
        $this->mailFrom = $mailFrom;
        return $this;
    }

    public function getMailTo(): string
    {
        return $this->mailTo;
    }

    public function setMailTo(string $mailTo): self
    {
        $this->mailTo = $mailTo;
        return $this;
    }

    public function getMailCopy(): string
    {
        return $this->mailCopy;
    }

    public function setMailCopy(string $mailCopy): self
    {
        $this->mailCopy = $mailCopy;
        return $this;
    }

    public function getMailBlindCopy(): string
    {
        return $this->mailBlindCopy;
    }

    public function setMailBlindCopy(string $mailBlindCopy): self
    {
        $this->mailBlindCopy = $mailBlindCopy;
        return $this;
    }

    public function getHeaders(): string
    {
        return $this->headers;
    }

    public function setHeaders(string $headers): self
    {
        $this->headers = $headers;
        return $this;
    }

    public function getResult(): string
    {
        return $this->result;
    }

    public function setResult(string $result): self
    {
        $this->result = $result;
        return $this;
    }

    public function getSysLanguageUid(): int
    {
        return $this->sysLanguageUid;
    }

    public function setSysLanguageUid(int $sysLanguageUid): self
    {
        $this->sysLanguageUid = $sysLanguageUid;
        return $this;
    }
}
