<?php

namespace Pluswerk\MailLogger\Logging;

use Pluswerk\MailLogger\Domain\Model\MailLog;
use Pluswerk\MailLogger\Domain\Repository\MailLogRepository;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\RawMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class LoggingTransport implements TransportInterface
{
    protected TransportInterface $originalTransport;
    protected MailLog $mailLog;

    public function __construct(TransportInterface $originalTransport)
    {
        $this->originalTransport = $originalTransport;
    }

    public function send(RawMessage $message, Envelope $envelope = null): ?SentMessage
    {
        $mailLogRepository = GeneralUtility::makeInstance(ObjectManager::class)->get(MailLogRepository::class);

        // write mail to log before send
        $this->getMailLog(); //just for init mail log
        $this->assignMailLog($message);
        $mailLogRepository->add($this->mailLog);
        GeneralUtility::makeInstance(PersistenceManager::class)->persistAll();

        $result = $this->originalTransport->send($message, $envelope);

        // write result to log after send
        $this->assignMailLog($message);
        $this->mailLog->setResult((string)(bool)$result);
        $mailLogRepository->update($this->mailLog);
        return $result;
    }

    public function __toString(): string
    {
        return $this->originalTransport->__toString();
    }

    public function getMailLog(): MailLog
    {
        return $this->mailLog ??= GeneralUtility::makeInstance(MailLog::class);
    }

    protected function assignMailLog($message): void
    {
        $messageBody = '';
        $bodyParts = $message->getBody()->getParts();
        foreach ($bodyParts as $bodyPart) {
            $mediaType = $bodyPart->getMediaType();
            if ($mediaType === 'text') {
                $messageBody .= $bodyPart->getBody();
            }
        }

        $this->mailLog->setMessage($messageBody);
        $this->mailLog->setSubject($message->getSubject());
        $this->mailLog->setMailFrom($this->addressesToString($message->getFrom()));
        $this->mailLog->setMailTo($this->addressesToString($message->getTo()));
        $this->mailLog->setMailCopy($this->addressesToString($message->getCc()));
        $this->mailLog->setMailBlindCopy($this->addressesToString($message->getBcc()));
        $this->mailLog->setHeaders($message->getHeaders()->toString());
    }

    protected function addressesToString(array $addresses): string
    {
        return implode(
            ', ',
            array_map(
                function (Address $address) {
                    return $address->getName() . ' <' . $address->getAddress() . '>';
                },
                $addresses
            )
        );
    }
}
