<?php

namespace Pluswerk\MailLogger\Tests\Functional\MailLogRepository;

final class NoAnonymizeLifeTime1minTest extends AbstractMailLogRepositoryTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFrontendRootPage(1, [
            'EXT:mail_logger/Configuration/TypoScript/setup.typoscript',
            'EXT:mail_logger/Tests/Fixtures/MailLogRepositoryTest/NoAnonymizeLifeTime1min.typoscript',
        ]);
    }
}
