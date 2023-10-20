<?php

declare(strict_types=1);

namespace PostHog\PostHogBundle\Adapter;

use PostHog\Client;
use PostHog\PostHog as PH;
use PostHog\PostHogBundle\Exception\NotInitializedException;

class PostHogAdapter
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
        PH::init(client: $this->client);
    }

    public function capture(array $message): bool
    {
        return PH::capture($message);
    }

    public function identify(array $message): bool
    {
        return PH::identify($message);
    }

    public function groupIdentify(array $message): bool
    {
        return PH::groupIdentify($message);
    }

    public function isFeatureEnabled(string $key, string $distinctId, array $groups = [], array $personProperties = [], array $groupProperties = [], bool $onlyEvaluateLocally = false, bool $sendFeatureFlagEvents = true): null|bool
    {
        return PH::isFeatureEnabled($key, $distinctId, $groups, $personProperties, $groupProperties, $onlyEvaluateLocally, $sendFeatureFlagEvents);
    }

    public function getFeatureFlag(string $key, string $distinctId, array $groups = [], array $personProperties = [], array $groupProperties = [], bool $onlyEvaluateLocally = false, bool $sendFeatureFlagEvents = true): null|bool|string
    {
        return PH::getFeatureFlag($key, $distinctId, $groups, $personProperties, $groupProperties, $onlyEvaluateLocally, $sendFeatureFlagEvents);
    }

    public function getAllFlags(string $distinctId, array $groups = [], array $personProperties = [], array $groupProperties = [], bool $onlyEvaluateLocally = false)
    {
        return PH::getAllFlags($distinctId, $groups, $personProperties, $groupProperties, $onlyEvaluateLocally);
    }

    public function fetchFeatureVariants(string $distinctId, array $groups = []): array
    {
        return PH::fetchFeatureVariants($distinctId, $groups);
    }

    public function alias(array $message): bool
    {
        return PH::alias($message);
    }

    public function raw(array $message): bool
    {
        return PH::raw($message);
    }

    /**
     * @return bool
     */
    public function flush(): bool
    {
        $result = PH::flush();

        if (is_bool($result)) {
            return $result;
        }

        if (is_string($result)) {
            $decoded = json_decode($result, true);

            return array_key_exists('status', $decoded);
        }

        return false;
    }
}
