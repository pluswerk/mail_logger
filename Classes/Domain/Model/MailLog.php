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
 * @method setTypoScriptKey(string $string)
 * @method setSubject(string $string)
 * @method setMessage(string $string)
 * @method setMailFrom(string $string)
 * @method setMailTo(string $string)
 * @method setMailCopy(string $string)
 * @method setMailBlindCopy(string $string)
 * @method setResult(string $string)
 * @method setHeaders(string $string)
 * @method string getHeaders()
 * @method setSysLanguageUid(int $var)
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
}
