<?php

namespace Pluswerk\MailLogger\Tests\Functional\MailLogRepository;

class AnonymizeDirectlyLifeTimeEmptyTest extends AbstractMailLogRepositoryTest
{
    public function setUp()
    {
        parent::setUp();
        $this->setUpFrontendRootPage(1, [
            'EXT:mail_logger/Configuration/TypoScript/setup.typoscript',
            'EXT:mail_logger/Tests/Fixtures/MailLogRepositoryTest/AnonymizeDirectlyLifeTimeEmpty.typoscript',
        ]);
    }
}
