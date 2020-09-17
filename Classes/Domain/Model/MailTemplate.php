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
 * Class MailTemplate
 */
class MailTemplate extends AbstractModel
{
    /**
     * @var string
     */
    protected $typoScriptKey = '';

    /**
     * @var string
     */
    protected $templatePathKey = '';

    /**
     * @var string
     */
    protected $title = '';

    /**
     * @var string
     */
    protected $subject = '';

    /**
     * @var string
     */
    protected $message = '';

    /**
     * @var string
     */
    protected $mailFromName = '';

    /**
     * @var string
     */
    protected $mailFromAddress = '';

    /**
     * comma separated
     *
     * @var string
     */
    protected $mailToNames = '';

    /**
     * comma separated
     *
     * @var string
     */
    protected $mailToAddresses = '';

    /**
     * comma separated
     *
     * @var string
     */
    protected $mailCopyAddresses = '';

    /**
     * comma separated
     *
     * @var string
     */
    protected $mailBlindCopyAddresses = '';

    /**
     * @var string
     */
    protected $dkimKey = '';

    public function getTypoScriptKey(): string
    {
        return $this->typoScriptKey;
    }

    public function setTypoScriptKey(string $typoScriptKey): self
    {
        $this->typoScriptKey = $typoScriptKey;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
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

    public function getMailFromName(): string
    {
        return $this->mailFromName;
    }

    public function setMailFromName(string $mailFromName): self
    {
        $this->mailFromName = $mailFromName;
        return $this;
    }

    public function getMailFromAddress(): string
    {
        return $this->mailFromAddress;
    }

    public function setMailFromAddress(string $mailFromAddress): self
    {
        $this->mailFromAddress = $mailFromAddress;
        return $this;
    }

    public function getMailToNames(): string
    {
        return $this->mailToNames;
    }

    public function setMailToNames(string $mailToNames): self
    {
        $this->mailToNames = $mailToNames;
        return $this;
    }

    public function getMailToAddresses(): string
    {
        return $this->mailToAddresses;
    }

    public function setMailToAddresses(string $mailToAddresses): self
    {
        $this->mailToAddresses = $mailToAddresses;
        return $this;
    }

    public function getMailCopyAddresses(): string
    {
        return $this->mailCopyAddresses;
    }

    public function setMailCopyAddresses(string $mailCopyAddresses): self
    {
        $this->mailCopyAddresses = $mailCopyAddresses;
        return $this;
    }

    public function getMailBlindCopyAddresses(): string
    {
        return $this->mailBlindCopyAddresses;
    }

    public function setMailBlindCopyAddresses(string $mailBlindCopyAddresses): self
    {
        $this->mailBlindCopyAddresses = $mailBlindCopyAddresses;
        return $this;
    }

    public function getDkimKey(): string
    {
        return $this->dkimKey;
    }

    public function setDkimKey(string $dkimKey): self
    {
        $this->dkimKey = $dkimKey;
        return $this;
    }

    public function getTemplatePathKey(): string
    {
        return $this->templatePathKey;
    }

    public function setTemplatePathKey(string $templatePathKey): self
    {
        $this->templatePathKey = $templatePathKey;
        return $this;
    }
}
