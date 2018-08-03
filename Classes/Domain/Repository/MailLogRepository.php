<?php

namespace Pluswerk\MailLogger\Domain\Repository;

/***
 *
 * This file is part of an "+Pluswerk AG" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2018 Sinian Zhang <sinian.zhang@pluswerk.ag>, +Pluswerk AG
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
     * @var string
     */
    protected $defaultAnonymizeAfter = '7 days';

    /**
     * @var string
     */
    protected $lifetime = '30 days';

    /**
     * @var string
     */
    protected $anonymizeAfter;

    /**
     * @var string
     */
    protected $anonymizeSymbol = '***';

    /**
     * @var bool
     */
    protected $anonymize = true;

    /**
     * @return void
     */
    public function initializeObject()
    {
        /** @var $querySettings \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings */
        $querySettings = $this->objectManager->get(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);

        // mail logger typoscript settings
        $configurationManager = $this->objectManager->get(ConfigurationManager::class);
        $fullSettings = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        $settings = $fullSettings['module.']['tx_maillogger.']['settings.'];

        $this->lifetime = $this->defaultLifetime;
        if (isset($settings['cleanup.']['lifetime'])) {
            $this->lifetime = $settings['cleanup.']['lifetime'];
        }
        $this->anonymizeAfter = $this->defaultAnonymizeAfter;
        if (isset($settings['cleanup.']['anonymizeAfter'])) {
            $this->anonymizeAfter = $settings['cleanup.']['anonymizeAfter'];
        }
        if (isset($settings['cleanup.']['anonymize'])) {
            $this->anonymize = (bool)$settings['cleanup.']['anonymize'];
        }

        // cleanup
        $this->cleanupDatabase();

        // anonymize
        $this->anonymizeAll();
    }

    /**
     * Delete old mail log entries (default: 30 days and hard deletion)
     * @return void
     */
    protected function cleanupDatabase()
    {
        if ($this->lifetime !== '') {
            foreach ($this->findOldMailLogRecords($this->lifetime) as $mailLog) {
                $this->remove($mailLog);
            }
        }
    }

    /**
     * Anonymize mail logs (default: after 7 days)
     * @return void
     */
    protected function anonymizeAll()
    {
        if ($this->anonymize) {
            foreach ($this->findOldMailLogRecords($this->anonymizeAfter) as $mailLog) {
                $this->update($mailLog);
            }
        }
    }

    /**
     * @param string $lifeTime e.g. 1 day, 30 days, 2 hours, etc.
     * @return MailLog[]
     */
    protected function findOldMailLogRecords($lifeTime)
    {
        $query = $this->createQuery();
        $query->matching($query->lessThanOrEqual('crdate', date_modify(new \DateTime(), '-' . $lifeTime)));
        return $query->execute();
    }

    /**
     * @param MailLog $mailLog
     * @return void
     */
    public function add($mailLog)
    {
        if ($mailLog->getCrdate() === null) {
            $mailLog->_setProperty('crdate', time());
        }
        if ($mailLog->getTstamp() === null) {
            $mailLog->_setProperty('tstamp', time());
        }
        $this->anonymizeMailLogIfNeeded($mailLog);
        parent::add($mailLog);
    }

    /**
     * @param MailLog $mailLog
     * @return void
     */
    public function update($mailLog)
    {
        if ($mailLog->getTstamp() === null) {
            $mailLog->_setProperty('tstamp', time());
        }
        $this->anonymizeMailLogIfNeeded($mailLog);
        parent::update($mailLog);
    }

    /**
     * @param MailLog $mailLog
     * @return void
     */
    protected function anonymizeMailLogIfNeeded(MailLog $mailLog)
    {
        if ($mailLog->getCrdate() === null) {
            throw new \InvalidArgumentException('MailLog must have a crdate');
        }
        if ($this->anonymize === false) {
            return;
        }
        if ($mailLog->getCrdate() > date_modify(new \DateTime(), '-' . $this->anonymizeAfter)->getTimestamp()) {
            return;
        }

        $mailLog->setSubject($this->anonymizeSymbol);
        $mailLog->setMessage($this->anonymizeSymbol);
        $mailLog->setMailFrom($this->anonymizeSymbol);
        $mailLog->setMailTo($this->anonymizeSymbol);
        $mailLog->setResult($this->anonymizeSymbol);
        $mailLog->setMailBlindCopy($this->anonymizeSymbol);
    }
}
