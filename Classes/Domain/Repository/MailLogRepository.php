<?php

namespace Pluswerk\MailLogger\Domain\Repository;

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

use Pluswerk\MailLogger\Domain\Model\MailLog;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 */
class MailLogRepository extends Repository
{
    /**
     * @var array
     */
    protected $defaultOrderings = [
        'crdate' => QueryInterface::ORDER_DESCENDING,
    ];

    /**
     * @var string
     */
    protected $defaultLifetime = '30 days';

    /**
     * @return void
     */
    public function initializeObject()
    {
        /** @var $querySettings \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings */
        $querySettings = $this->objectManager->get(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);

        $this->cleanup();
    }

    /**
     * Delete old mail log entries (default: 30 days and hard deletion)
     * @return void
     */
    protected function cleanup()
    {
        $configurationManager = $this->objectManager->get(ConfigurationManager::class);
        $fullSettings = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        $lifetime = $fullSettings['module.']['tx_maillogger.']['settings.']['cleanup.']['lifetime'] ?: $this->defaultLifetime;
        foreach ($this->findOldMailLogRecords($lifetime) as $mailLog) {
            $this->remove($mailLog);
        }
    }

    /**
     * @param string $lifeTime e.g. 1 day, 30 days, 2 hours, etc.
     * @return MailLog[]
     */
    protected function findOldMailLogRecords($lifeTime)
    {
        $query = $this->createQuery();
        $now = new \DateTime();
        $query->matching($query->lessThanOrEqual('crdate', $now->modify('-' . $lifeTime)));
        return $query->execute();
    }
}
