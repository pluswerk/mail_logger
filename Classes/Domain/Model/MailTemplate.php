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
     * @return string
     */
    public function getTypoScriptKey(): string
    {
        return $this->typoScriptKey;
    }

    /**
     * @param string $typoScriptKey
     * @return self
     */
    public function setTypoScriptKey($typoScriptKey)
    {
        $this->typoScriptKey = $typoScriptKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     * @return self
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return self
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getMailFromName(): string
    {
        return $this->mailFromName;
    }

    /**
     * @param string $mailFromName
     * @return self
     */
    public function setMailFromName($mailFromName)
    {
        $this->mailFromName = $mailFromName;
        return $this;
    }

    /**
     * @return string
     */
    public function getMailFromAddress(): string
    {
        return $this->mailFromAddress;
    }

    /**
     * @param string $mailFromAddress
     * @return self
     */
    public function setMailFromAddress($mailFromAddress)
    {
        $this->mailFromAddress = $mailFromAddress;
        return $this;
    }

    /**
     * @return string
     */
    public function getMailToNames(): string
    {
        return $this->mailToNames;
    }

    /**
     * @param string $mailToNames
     * @return self
     */
    public function setMailToNames($mailToNames)
    {
        $this->mailToNames = $mailToNames;
        return $this;
    }

    /**
     * @return string
     */
    public function getMailToAddresses(): string
    {
        return $this->mailToAddresses;
    }

    /**
     * @param string $mailToAddresses
     * @return self
     */
    public function setMailToAddresses($mailToAddresses)
    {
        $this->mailToAddresses = $mailToAddresses;
        return $this;
    }

    /**
     * @return string
     */
    public function getMailCopyAddresses(): string
    {
        return $this->mailCopyAddresses;
    }

    /**
     * @param string $mailCopyAddresses
     * @return self
     */
    public function setMailCopyAddresses($mailCopyAddresses)
    {
        $this->mailCopyAddresses = $mailCopyAddresses;
        return $this;
    }

    /**
     * @return string
     */
    public function getMailBlindCopyAddresses(): string
    {
        return $this->mailBlindCopyAddresses;
    }

    /**
     * @param string $mailBlindCopyAddresses
     * @return self
     */
    public function setMailBlindCopyAddresses($mailBlindCopyAddresses)
    {
        $this->mailBlindCopyAddresses = $mailBlindCopyAddresses;
        return $this;
    }
}
