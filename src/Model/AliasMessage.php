<?php

declare(strict_types=1);

namespace PostHog\PostHogBundle\Model;

class AliasMessage
{
    public function __construct(
        public string $distinctId,
        public string $alias,
    ) {
    }

    public function toArray()
    {
        return [
            'distinctId' => $this->distinctId,
            'alias' => $this->alias,
        ];
    }
}
