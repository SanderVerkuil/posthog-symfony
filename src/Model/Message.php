<?php

declare(strict_types=1);

namespace PostHog\PostHogBundle\Model;

class Message
{
    public function __construct(
        public string $event,
        public string $distinctId,
        public array $properties,
    ) {
    }

    public function toArray(): array
    {
        return [
            'distinctId' => $this->distinctId,
            'properties' => $this->properties,
        ];
    }
}
