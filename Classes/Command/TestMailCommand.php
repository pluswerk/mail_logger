<?php

declare(strict_types=1);

namespace Pluswerk\MailLogger\Command;

use Pluswerk\MailLogger\Utility\MailUtility;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Mail\MailerInterface;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TestMailCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setDescription('Sends a test mail.')
            ->setHelp(
                'This command sends a mail with your complete setup and logs it. Additionally you can provide a mail template key and test your template in the default language, of course without variables.'
            )
            ->addOption('textLength', 'l', InputArgument::OPTIONAL, 'Mail Template Key to test a mail template', 26)
            ->addArgument('addressto', InputArgument::REQUIRED, 'Mail Address to send this testmail to')
            ->addArgument('templatekey', InputArgument::OPTIONAL, 'Mail Template Key to test a mail template', '');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $args = $input->getArguments();
        if ($args['templatekey'] !== '') {
            $mail = MailUtility::getMailByKey($args['templatekey'], null);
        } else {
            $mail = GeneralUtility::makeInstance(MailMessage::class);
            $mail
                ->setSubject('Testmail')
                ->html(str_pad('This is a testmail (html).', (int)$input->getOption('textLength'), '_ '));
        }

        $mail->addTo($args['addressto']);

        if ($mail->send()) {
            $output->writeln('Successfully send mail');
            return 0;
        }

        $output->writeln('<error>Error on send</error>');
        return 1;
    }
}
