<?php

declare(strict_types=1);

namespace PostHog\PostHogBundle;

use PostHog\PostHogBundle\Model\AliasMessage;
use PostHog\PostHogBundle\Model\GroupIdentifyMessage;
use PostHog\PostHogBundle\Model\IdentifyMessage;
use PostHog\PostHogBundle\Model\Message;

interface PostHogInterface
{
    public function capture(Message $message): bool;

    public function identify(IdentifyMessage $message): bool;

    public function groupIdentify(GroupIdentifyMessage $message): bool;

    /**
     * @param string[]             $groups
     * @param array<string, mixed> $personProperties
     * @param array<string, mixed> $groupProperties
     */
    public function isFeatureEnabled(string $key, string $distinctId, array $groups, array $personProperties, array $groupProperties, bool $onlyEvaluateLocally, bool $sendFeatureFlagEvents): bool|null;

    /**
     * @param string[]             $groups
     * @param array<string, mixed> $personProperties
     * @param array<string, mixed> $groupProperties
     */
    public function getFeatureFlag(string $key, string $distinctId, array $groups, array $personProperties, array $groupProperties, bool $onlyEvaluateLocally, bool $sendFeatureFlagEvents): bool|string|null;

    /**
     * @param string[]             $groups
     * @param array<string, mixed> $personProperties
     * @param array<string, mixed> $groupProperties
     *
     * @return string[]
     */
    public function getAllFlags(string $distinctId, array $groups, array $personProperties, array $groupProperties, bool $onlyEvaluateLocally): array;

    /**
     * @param string[] $groups
     *
     * @return string[]
     */
    public function fetchFeatureVariants(string $distinctId, array $groups): array;

    public function alias(AliasMessage $message): bool;

    /**
     * @param array<string, mixed> $message
     */
    public function raw(array $message): mixed;

    public function flush(): bool;
}
