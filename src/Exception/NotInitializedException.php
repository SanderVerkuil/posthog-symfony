<?php

declare(strict_types=1);

namespace PostHog\PostHogBundle\Exception;

use Throwable;

class NotInitializedException extends \Exception
{
    public function __construct(int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct('Client is not initialized, please initialze first.', $code, $previous);
    }
}
