<?php

namespace Pluswerk\MailLogger\Form\Field;

/***
 *
 * This file is part of an "+Pluswerk AG" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2019 Felix König <felix.koenig@pluswerk.ag>, +Pluswerk AG
 *
 ***/

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\TagBuilder;
use TYPO3\CMS\Install\ViewHelpers\Form\TypoScriptConstantsViewHelper;

class TextAreaUserFunction
{

    /**
     * Tag builder instance
     *
     * @var \TYPO3\CMS\Fluid\Core\ViewHelper\TagBuilder
     */
    protected $tag;

    /**
     * constructor of this class
     */
    public function __construct()
    {
        $this->tag = GeneralUtility::makeInstance(TagBuilder::class);
    }

    /**
     * render textarea for extConf
     *
     * @param array $parameter
     * @param TypoScriptConstantsViewHelper $parentObject
     * @return string
     */
    public function render(array $parameter, TypoScriptConstantsViewHelper $parentObject): string
    {
        $this->tag->setTagName('textarea');
        $this->tag->forceClosingTag(true);
        $this->tag->addAttribute('cols', 100);
        $this->tag->addAttribute('rows', 30);
        $this->tag->addAttribute('name', $parameter['fieldName']);
        $this->tag->addAttribute('id', 'em-' . $parameter['fieldName']);
        if ($parameter['fieldValue'] !== null) {
            $this->tag->setContent(trim($parameter['fieldValue']));
        }
        return $this->tag->render();
    }
}
