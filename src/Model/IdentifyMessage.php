<?php

declare(strict_types=1);

namespace PostHog\PostHogBundle\Model;

class IdentifyMessage
{
    public function __construct(
        public string $distinctId,
        public array $set = [],
    ) {
    }

    public function toArray(): array
    {
        return [
            'distinctId' => $this->distinctId,
            '$set' => $this->set,
        ];
    }
}
