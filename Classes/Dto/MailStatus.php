<?php

declare(strict_types=1);

namespace Pluswerk\MailLogger\Dto;

enum MailStatus: int
{
    case UNKNOWN = 0;
    case SENT_OK = 1;
    case NOT_SENT = 2;
    case QUEUED = 3;
}
