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

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * @method int getTstamp()
 * @method setTstamp(int $value)
 * @method int getCrdate()
 */
abstract class AbstractModel extends AbstractEntity
{

    /**
     * @var int
     */
    protected $tstamp;

    /**
     * @var int
     */
    protected $crdate;

    /**
     * dynamic getter and setter
     *
     * @param string $methodName
     * @param array $params
     * @return mixed|NULL
     */
    public function __call($methodName, $params)
    {
        $methodPrefix = substr($methodName, 0, 3);
        $attributeName = lcfirst(substr($methodName, 3));
        $attributePlName = $attributeName . 's';

        if ($methodPrefix === 'set') {
            $value = $params[0];
            $this->{$attributeName} = $value;
        } elseif ($methodPrefix === 'get') {
            return $this->{$attributeName};
        } elseif ($methodPrefix === 'add') {
            $this->{$attributePlName}->attach($params[0]);
        } elseif (0 === strpos($methodName, 'remove')) {
            $this->{$attributePlName}->detach($params[0]);
        }
        return null;
    }
}
