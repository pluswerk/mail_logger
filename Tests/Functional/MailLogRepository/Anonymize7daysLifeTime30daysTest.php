<?php

namespace Pluswerk\MailLogger\Tests\Functional\MailLogRepository;

final class Anonymize7daysLifeTime30daysTest extends AbstractMailLogRepositoryTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFrontendRootPage(1, [
            'EXT:mail_logger/Configuration/TypoScript/setup.typoscript',
            'EXT:mail_logger/Tests/Fixtures/MailLogRepositoryTest/Anonymize7daysLifeTime30days.typoscript',
        ]);
    }
}
