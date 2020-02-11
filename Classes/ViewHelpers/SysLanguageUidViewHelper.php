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

namespace Pluswerk\MailLogger\ViewHelpers;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 */
class SysLanguageUidViewHelper extends AbstractViewHelper
{
    public function initializeArguments()
    {
        $this->registerArgument('uid', 'int', 'get sys_language.title by sys_language.uid');
    }

    /**
     * @return string
     */
    public function render()
    {
        $uid = (int)$this->arguments['uid'];
        if ($uid === 0) {
            $language = 'Default';
        } elseif ($uid === -1) {
            $language = 'All';
        } else {
            /** @var \TYPO3\CMS\Core\Database\DatabaseConnection $databaseConnection */
            $databaseConnection = $GLOBALS['TYPO3_DB'];
            $res = $databaseConnection->exec_SELECTgetSingleRow('title', 'sys_language', 'uid = ' . $uid);
            $language = $res['title'];
        }
        return $language;
    }
}
