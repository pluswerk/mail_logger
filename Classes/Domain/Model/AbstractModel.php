<?php

declare(strict_types=1);

namespace Pluswerk\MailLogger\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

abstract class AbstractModel extends AbstractEntity
{
    protected int|null $tstamp = 0;

    protected int|null $crdate = 0;

    public function getTstamp(): ?int
    {
        return $this->tstamp;
    }

    public function setTstamp(?int $tstamp): self
    {
        $this->tstamp = $tstamp;
        return $this;
    }

    public function getCrdate(): ?int
    {
        return $this->crdate;
    }

    public function setCrdate(?int $crdate): self
    {
        $this->crdate = $crdate;
        return $this;
    }
}
