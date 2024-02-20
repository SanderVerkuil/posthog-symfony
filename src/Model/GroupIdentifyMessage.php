<?php

declare(strict_types=1);

namespace PostHog\PostHogBundle\Model;

class GroupIdentifyMessage
{
    /**
     * @param array<string, string> $groupProperties
     */
    public function __construct(
        public string $groupType,
        public string $groupKey,
        public array $groupProperties,
    ) {
    }

    public function toArray(): array
    {
        return [
            'properties' => $this->groupProperties,
        ];
    }
}
