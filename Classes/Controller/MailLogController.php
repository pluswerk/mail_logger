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

namespace Pluswerk\MailLogger\Controller;

use Pluswerk\MailLogger\Domain\Model\MailLog;
use Pluswerk\MailLogger\Domain\Repository\MailLogRepository;
use TYPO3\CMS\Core\Pagination\SimplePagination;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;

/**
 */
class MailLogController extends ActionController
{
    /**
     * @var \Pluswerk\MailLogger\Domain\Repository\MailLogRepository
     */
    protected $mailLogRepository;

    public function injectMailLogRepository(MailLogRepository $mailLogRepository): void
    {
        $this->mailLogRepository = $mailLogRepository;
    }

    /**
     * action dashboard
     *
     * @return void
     */
    public function dashboardAction(): void
    {
        $pageRenderer = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Page\PageRenderer::class);
        $pageRenderer->loadRequireJsModule('TYPO3/CMS/MailLogger/MailLogController');
        $pageRenderer->loadRequireJsModule('TYPO3/CMS/MailLogger/DashboardController');

        $mailLogs = $this->mailLogRepository->findAll();
        $currentPageNumber = 1;
        $currentPageNumber = $this->request->hasArgument('currentPageNumber') ? $this->request->getArgument('currentPageNumber') : $currentPageNumber;
        $paginator = new QueryResultPaginator($mailLogs,(int)$currentPageNumber,10);
        $pagination = new SimplePagination($paginator);

        $this->view->assignMultiple([
            'mailLogs'=> $paginator->getPaginatedItems(),
            'pagination' => $pagination,
            'paginator' => $paginator,
            'numbersOfPages' => $pagination->getAllPageNumbers()
        ]);
    }

    /**
     * action show
     *
     * @param \Pluswerk\MailLogger\Domain\Model\MailLog $mailLog
     * @return void
     */
    public function showAction(MailLog $mailLog): void
    {
        $this->view->assign('mailLog', $mailLog);
    }
}
