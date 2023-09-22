<?php

namespace Pluswerk\MailLogger\Tests\Functional\MailLogRepository;

final class AnonymizeDirectlyLifeTimeEmptyTest extends AbstractMailLogRepositoryTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFrontendRootPage(1, [
            'EXT:mail_logger/Configuration/TypoScript/setup.typoscript',
            'EXT:mail_logger/Tests/Fixtures/MailLogRepositoryTest/AnonymizeDirectlyLifeTimeEmpty.typoscript',
        ]);
    }
}
