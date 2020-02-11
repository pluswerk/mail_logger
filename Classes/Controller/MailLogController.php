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

namespace Pluswerk\MailLogger\Controller;

use Pluswerk\MailLogger\Domain\Model\MailLog;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 */
class MailLogController extends ActionController
{
    /**
     * @var \Pluswerk\MailLogger\Domain\Repository\MailLogRepository
     * @inject
     */
    protected $mailLogRepository;

    /**
     * action dashboard
     *
     * @return void
     */
    public function dashboardAction()
    {
        $this->view->assign('mailLogs', $this->mailLogRepository->findAll());
    }

    /**
     * action show
     *
     * @param \Pluswerk\MailLogger\Domain\Model\MailLog $mailLog
     * @return void
     */
    public function showAction(MailLog $mailLog)
    {
        $this->view->assign('mailLog', $mailLog);
    }
}
