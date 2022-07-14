<?php

/** @noinspection PhpComposerExtensionStubsInspection */

namespace Pluswerk\MailLogger\Tests\Functional\MailLogRepository;

use Faker\Factory;
use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use Pluswerk\MailLogger\Domain\Model\MailLog;
use Pluswerk\MailLogger\Domain\Repository\MailLogRepository;
use Spatie\Snapshots\MatchesSnapshots;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

abstract class AbstractMailLogRepositoryTest extends FunctionalTestCase
{
    use MatchesSnapshots;

    /**
     * @var array
     */
    protected $testExtensionsToLoad = [
        'typo3conf/ext/mail_logger',
    ];
    /**
     * @var string
     */
    protected $delayAnonymize = '8 days';

    public function setUp()
    {
        parent::setUp();
        $this->importDataSet('ntf://Database/pages.xml');
    }

    public function testInitializeObject()
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $persistenceManager = $objectManager->get(PersistenceManager::class);

        $mailLogRepository = $this->initializeObjectPart($objectManager, $persistenceManager);

        $this->assertMatchesJsonSnapshot(json_encode([
            'lifetime' => $this->getObjectAttribute($mailLogRepository, 'lifetime'),
            'anonymize' => $this->getObjectAttribute($mailLogRepository, 'anonymize'),
            'anonymizeAfter' => $this->getObjectAttribute($mailLogRepository, 'anonymizeAfter'),
            'anonymizeSymbol' => $this->getObjectAttribute($mailLogRepository, 'anonymizeSymbol'),
        ]));
    }

    public function testAdd()
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $persistenceManager = $objectManager->get(PersistenceManager::class);

        $mailLogRepository = $this->initializeObjectPart($objectManager, $persistenceManager);

        $mailLog = $this->addingPart($mailLogRepository, $persistenceManager, 558);

        $this->assertModelSnapshot($mailLog);
    }

    public function testUpdate()
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $persistenceManager = $objectManager->get(PersistenceManager::class);

        $mailLogRepository = $this->initializeObjectPart($objectManager, $persistenceManager);

        $mailLog = $this->addingPart($mailLogRepository, $persistenceManager, 555);

        $mailLog = $this->updatingPart($mailLogRepository, $persistenceManager, $mailLog);

        $this->assertModelSnapshot($mailLog);
    }

    public function testUpdateWithDelayAnonymize()
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $persistenceManager = $objectManager->get(PersistenceManager::class);

        $mailLogRepository = $this->initializeObjectPart($objectManager, $persistenceManager);
        /** @var MailLog $mailLog */
        $mailLog = $this->addingPart($mailLogRepository, $persistenceManager, 2345);

        $mailLog->_setProperty('crdate', date_modify(new \DateTime(), '-' . $this->delayAnonymize)->getTimestamp() - 5);

        $mailLog = $this->updatingPart($mailLogRepository, $persistenceManager, $mailLog);

        $this->assertModelSnapshot($mailLog);
    }

    public function testCleanupDatabase()
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $persistenceManager = $objectManager->get(PersistenceManager::class);

        $mailLogRepository = $this->initializeObjectPart($objectManager, $persistenceManager);

        $this->addingPart($mailLogRepository, $persistenceManager, 789);

        $this->getDatabaseConnection()->updateArray('tx_maillogger_domain_model_maillog', ['uid' => '1'], ['tstamp' => 0, 'crdate' => 0]);

        $this->cleanupDatabasePart($mailLogRepository, $persistenceManager);

        /** @var MailLog $mailLog */
        $mailLog = $mailLogRepository->findAll()->getFirst();
        $this->assertModelSnapshot($mailLog);
    }

    public function testAnonymizeAll()
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $persistenceManager = $objectManager->get(PersistenceManager::class);

        $mailLogRepository = $this->initializeObjectPart($objectManager, $persistenceManager);

        $this->addingPart($mailLogRepository, $persistenceManager, 7894);

        $timestamp = date_modify(new \DateTime(), '-' . $this->delayAnonymize)->getTimestamp() - 5;
        $this->getDatabaseConnection()->updateArray('tx_maillogger_domain_model_maillog', ['uid' => '1'], ['tstamp' => $timestamp, 'crdate' => $timestamp]);
        $persistenceManager->clearState();

        $this->anonymizeAllPart($mailLogRepository, $persistenceManager);

        /** @var MailLog $mailLog */
        $mailLog = $mailLogRepository->findAll()->getFirst();
        $this->assertModelSnapshot($mailLog);
    }

    /**
     * @param $objectManager
     * @param $persistenceManager
     * @return MailLogRepository
     */
    protected function initializeObjectPart(ObjectManager $objectManager, PersistenceManager $persistenceManager)
    {
        $mailLogRepository = new MailLogRepository($objectManager);
        $mailLogRepository->injectPersistenceManager($persistenceManager);
        $mailLogRepository->initializeObject();
        return $mailLogRepository;
    }

    /**
     * @param int $seed
     * @return MailLog
     */
    protected function getNewMailLog($seed)
    {
        $faker = Factory::create();
        $faker->seed($seed);
        $mailLog = new MailLog();
        $mailLog->setTypoScriptKey($faker->slug($faker->numberBetween(4, 10)));
        $mailLog->setSubject($faker->realText($faker->numberBetween(10, 50)));
        $mailLog->setMailTo($faker->email);
        $mailLog->setMailFrom($faker->email);
        $mailLog->setMailCopy($faker->email);
        $mailLog->setMailBlindCopy($faker->email);
        $mailLog->setHeaders($faker->text($faker->numberBetween(10, 50)));
        $mailLog->setMessage($faker->realText($faker->numberBetween(10, 200)));
        return $mailLog;
    }

    /**
     * @param MailLogRepository $mailLogRepository
     * @param PersistenceManager $persistenceManager
     * @param int $seed
     * @return MailLog
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\NotImplementedException
     */
    protected function addingPart(MailLogRepository $mailLogRepository, PersistenceManager $persistenceManager, $seed)
    {
        $mailLogRepository->add($this->getNewMailLog($seed));
        $persistenceManager->persistAll();
        $persistenceManager->clearState();
        /** @var MailLog $mailLog */
        $mailLog = $mailLogRepository->findAll()->getFirst();
        $persistenceManager->persistAll();
//        $persistenceManager->clearState();
        return $mailLog;
    }

    /**
     * @param MailLogRepository $mailLogRepository
     * @param PersistenceManager $persistenceManager
     * @param MailLog $mailLog
     * @return MailLog
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\NotImplementedException
     */
    protected function updatingPart(MailLogRepository $mailLogRepository, PersistenceManager $persistenceManager, MailLog $mailLog)
    {
        $mailLogRepository->update($mailLog);
        $persistenceManager->persistAll();
        $persistenceManager->clearState();
        /** @var MailLog $mailLogResult */
        $mailLogResult = $mailLogRepository->findAll()->getFirst();
        $persistenceManager->persistAll();
        $persistenceManager->clearState();
        return $mailLogResult;
    }

    /**
     * @param MailLogRepository $mailLogRepository
     * @param PersistenceManager $persistenceManager
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\NotImplementedException
     */
    protected function cleanupDatabasePart(MailLogRepository $mailLogRepository, PersistenceManager $persistenceManager)
    {
        $this->callInaccessibleMethod($mailLogRepository, 'cleanupDatabase');
        $persistenceManager->persistAll();
        $persistenceManager->clearState();
    }

    /**
     * @param MailLogRepository $mailLogRepository
     * @param PersistenceManager $persistenceManager
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\NotImplementedException
     */
    protected function anonymizeAllPart(MailLogRepository $mailLogRepository, PersistenceManager $persistenceManager)
    {
        $this->callInaccessibleMethod($mailLogRepository, 'anonymizeAll');
        $persistenceManager->persistAll();
        $persistenceManager->clearState();
    }

    protected function assertModelSnapshot($model)
    {
        $array = $model;
        if ($model instanceof AbstractDomainObject) {
            $array = $model->_getProperties();
            unset($array['tstamp'], $array['crdate']);
        }
        $this->assertMatchesJsonSnapshot(json_encode($array));
    }
}
