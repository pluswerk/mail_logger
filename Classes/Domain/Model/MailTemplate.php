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
 * @method string getTypoScriptKey()
 * @method string getSubject()
 * @method string getMessage()
 * @method string getMailFromAddress()
 * @method string getMailToNames()
 * @method string getMailToAddresses()
 * @method string getMailCopyAddresses()
 * @method string getMailBlindCopyAddresses()
 */
class MailTemplate extends AbstractModel
{

    /**
     * @var string
     */
    protected $typoScriptKey;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var string
     */
    protected $mailFromName;

    /**
     * @var string
     */
    protected $mailFromAddress;

    /**
     * comma separated
     *
     * @var string
     */
    protected $mailToNames;

    /**
     * comma separated
     *
     * @var string
     */
    protected $mailToAddresses;

    /**
     * comma separated
     *
     * @var string
     */
    protected $mailCopyAddresses;

    /**
     * comma separated
     *
     * @var string
     */
    protected $mailBlindCopyAddresses;
}
