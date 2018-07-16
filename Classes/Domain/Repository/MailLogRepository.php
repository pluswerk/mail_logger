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
     * @var string
     */
    protected $mailLoggerSettings = '';

    /**
     * @var string
     */
    protected $anonymizeSymbol = '***';

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
        $this->mailLoggerSettings = $fullSettings['module.']['tx_maillogger.']['settings.'];

        // cleanup
        $this->cleanup();
    }

    /**
     * Delete old mail log entries (default: 30 days and hard deletion) and anonymize mail log too
     * @return void
     */
    protected function cleanup()
    {
        // delete mail logs
        $lifetime = $this->mailLoggerSettings['cleanup.']['lifetime'] ?: $this->defaultLifetime;
        foreach ($this->findOldMailLogRecords($lifetime) as $mailLog) {
            $this->remove($mailLog);
        }

        // anonymize mail logs
        $anonymizeAfter = $this->mailLoggerSettings['cleanup.']['anonymizeAfter'] ?: 0;
        // if anonymizeAfter is set
        if ($this->mailLoggerSettings['cleanup.']['anonymize'] && $anonymizeAfter) {
            foreach ($this->findOldMailLogRecords($anonymizeAfter) as $mailLog) {
                $this->anonymizeMailLog($mailLog);
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
        $now = new \DateTime();
        $query->matching($query->lessThanOrEqual('crdate', $now->modify('-' . $lifeTime)));
        return $query->execute();
    }

    /**
     * @param MailLog $object
     * @return void
     */
    public function add($object)
    {
        // if anonymizeAfter is not set
        $anonymizeAfter = $this->mailLoggerSettings['cleanup.']['anonymizeAfter'] ?: 0;
        if ($this->mailLoggerSettings['cleanup.']['anonymize'] && !$anonymizeAfter) {
            $this->anonymizeMailLog($object);
        }
        parent::add($object);
	}

    /**
     * @param MailLog $object
     * @return void
     */
    public function update($object)
    {
        // if anonymizeAfter is not set
        $anonymizeAfter = $this->mailLoggerSettings['cleanup.']['anonymizeAfter'] ?: 0;
        if ($this->mailLoggerSettings['cleanup.']['anonymize'] && !$anonymizeAfter) {
            $this->anonymizeMailLog($object);
        }
        parent::update($object);
    }

    /**
     * @param MailLog $object
     * @return void
     */
	protected function anonymizeMailLog($object) {
        if (strlen($object->getSubject())) {
            $object->setSubject($this->anonymizeSymbol);
        }
        if (strlen($object->getMessage())) {
            $object->setMessage($this->anonymizeSymbol);
        }
        if (strlen($object->getMailFrom())) {
            $object->setMailFrom($this->anonymizeSymbol);
        }
        if (strlen($object->getMailTo())) {
            $object->setMailTo($this->anonymizeSymbol);
        }
        if (strlen($object->getMailCopy())) {
            $object->setResult($this->anonymizeSymbol);
        }
        if (strlen($object->getMailBlindCopy())) {
            $object->setMailBlindCopy($this->anonymizeSymbol);
        }
    }
}
