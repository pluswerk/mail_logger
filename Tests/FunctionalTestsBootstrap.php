<?php

 declare(strict_types=1);

namespace Pluswerk\MailLogger\Tests;

use TYPO3\TestingFramework\Core\Testbase;

call_user_func(static function (): void {
    $testbase = new Testbase();
    $testbase->defineOriginalRootPath();
    $testbase->createDirectory($testbase->getWebRoot() . 'typo3temp/var/tests');
    $testbase->createDirectory($testbase->getWebRoot() . 'typo3temp/var/transient');
});
