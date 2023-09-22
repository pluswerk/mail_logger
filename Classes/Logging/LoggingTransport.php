<?php

declare(strict_types=1);

namespace Pluswerk\MailLogger\Logging;

use Pluswerk\MailLogger\Domain\Model\MailLog;
use Pluswerk\MailLogger\Domain\Model\TemplateBasedMailMessage;
use Pluswerk\MailLogger\Domain\Repository\MailLogRepository;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\AbstractMultipartPart;
use Symfony\Component\Mime\Part\AbstractPart;
use Symfony\Component\Mime\Part\TextPart;
use Symfony\Component\Mime\RawMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class LoggingTransport implements TransportInterface, \Stringable
{
    public function __construct(protected TransportInterface $originalTransport)
    {
    }

    public function send(RawMessage $message, Envelope $envelope = null): ?SentMessage
    {
        $mailLogRepository = GeneralUtility::makeInstance(MailLogRepository::class);

        // write mail to log before send
        $mailLog = GeneralUtility::makeInstance(MailLog::class);
        $this->assignMailLog($mailLog, $message);
        $mailLogRepository->add($mailLog);
        GeneralUtility::makeInstance(PersistenceManager::class)->persistAll();

        $result = $this->originalTransport->send($message, $envelope);

        // write result to log after send
        $this->assignMailLog($mailLog, $message);
        $mailLog->setResult((string)(bool)$result);
        $mailLogRepository->update($mailLog);
        return $result;
    }

    public function __toString(): string
    {
        return $this->originalTransport->__toString();
    }

    protected function assignMailLog(MailLog $mailLog, RawMessage $message): void
    {
        if (!$message instanceof Email) {
            return;
        }

        $messageBody = $message->getBody();
        $mailLog->setMessage($this->getBodyAsHtml($messageBody));
        $mailLog->setSubject($message->getSubject());
        $mailLog->setMailFrom($this->addressesToString($message->getFrom()));
        $mailLog->setMailTo($this->addressesToString($message->getTo()));
        $mailLog->setMailCopy($this->addressesToString($message->getCc()));
        $mailLog->setMailBlindCopy($this->addressesToString($message->getBcc()));
        $mailLog->setHeaders($message->getHeaders()->toString());
        if ($message instanceof TemplateBasedMailMessage) {
            $mailLog->setTypoScriptKey($message->getTypoScriptKey());
        }
    }

    protected function getBodyAsHtml(AbstractPart $part): string
    {
        if ($part instanceof AbstractMultipartPart) {
            $messageString = '';
            foreach ($part->getParts() as $childPart) {
                $messageString .= $this->getBodyAsHtml($childPart);
                $messageString .= '----------------------------------------<br>';
            }

            return $messageString;
        }

        $body = $part instanceof TextPart && $part->getMediaType() === 'text' ? $part->getBody() : $part->asDebugString();
        $body = str_replace(["\t", "\r"], '', $body);
        if ($part->getMediaSubtype() === 'plain') {
            $body = str_replace(PHP_EOL, '<br>', $body);
        } else {
            $body = str_replace(PHP_EOL, '', $body);
        }

        return $body . '<br>';
    }

    /**
     * @param Address[] $addresses
     */
    protected function addressesToString(array $addresses): string
    {
        return implode(
            ', ',
            array_map(
                static fn(Address $address): string => $address->getName() . ' <' . $address->getAddress() . '>',
                $addresses
            )
        );
    }
}
