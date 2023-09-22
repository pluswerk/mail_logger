<?php

declare(strict_types=1);

namespace Pluswerk\MailLogger\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Page\PageRenderer;
use Pluswerk\MailLogger\Domain\Model\MailLog;
use Pluswerk\MailLogger\Domain\Repository\MailLogRepository;
use TYPO3\CMS\Core\Pagination\SimplePagination;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;

class MailLogController extends ActionController
{
    public function __construct(
        private readonly MailLogRepository $mailLogRepository,
        private readonly ModuleTemplateFactory $moduleTemplateFactory,
    ) {
    }

    /**
     * action dashboard
     */
    public function dashboardAction(): ResponseInterface
    {
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        // Add required js files.
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        assert($pageRenderer instanceof PageRenderer);
        $pageRenderer->loadRequireJsModule('TYPO3/CMS/MailLogger/Main');

        // Assign all logged mails to template.
        $this->view->assign('mailLogs', $this->mailLogRepository->findAll());

        $moduleTemplate->setContent($this->view->render());
        return $this->htmlResponse($moduleTemplate->renderContent());
    }

    /**
     * action show
     */
    public function showAction(MailLog $mailLog): ResponseInterface
    {
        $this->view->assign('mailLog', $mailLog);

        return $this->htmlResponse();
    }
}
