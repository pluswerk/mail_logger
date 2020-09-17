<?php

/***
 *
 * This file is part of an "+Pluswerk AG" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2019 Felix König <felix.koenig@pluswerk.ag>, +Pluswerk AG
 *
 ***/

declare(strict_types=1);

namespace Pluswerk\MailLogger\Command;

use Pluswerk\MailLogger\Domain\Model\LoggableMailMessage;
use Pluswerk\MailLogger\Utility\MailUtility;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

class TestMailCommand extends Command
{
    protected function configure()
    {
        $this
            ->setDescription('Sends a test mail.')
            ->setHelp(
                'This command sends a mail with your complete setup and logs it. Additionally you can provide a mail template key and test your template in the default language, of course without variables.'
            )
            ->addArgument('addressto', InputArgument::REQUIRED, 'Mail Address to send this testmail to')
            ->addArgument('templatekey', InputArgument::OPTIONAL, 'Mail Template Key to test a mail template', '');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $args = $input->getArguments();
        if ($args['templatekey'] !== '') {
            $mail = MailUtility::getMailByKey($args['templatekey'], null);
        } else {
            $mail = GeneralUtility::makeInstance(ObjectManager::class)->get(LoggableMailMessage::class);
            $mail
                ->setSubject('Testmail')
                ->text('This is a testmail.');
        }
        $mail->addTo($args['addressto']);
        return $mail->send() ? 0 : 1;
    }
}
