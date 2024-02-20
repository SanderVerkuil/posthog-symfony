<?php

declare(strict_types=1);

namespace PostHog\PostHogBundle\Adapter;

use PostHog\Client;
use PostHog\PostHog as PH;
use PostHog\PostHogBundle\Model\AliasMessage;
use PostHog\PostHogBundle\Model\GroupIdentifyMessage;
use PostHog\PostHogBundle\Model\IdentifyMessage;
use PostHog\PostHogBundle\Model\Message;
use PostHog\PostHogBundle\PostHogInterface;

class PostHogAdapter implements PostHogInterface
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
        PH::init(client: $this->client);
    }

    public function capture(Message $message): bool
    {
        return $this->client->capture($message->toArray());
    }

    public function identify(IdentifyMessage $message): bool
    {
        return $this->client->identify($message->toArray());
    }

    public function groupIdentify(GroupIdentifyMessage $message): bool
    {
        return $this->capture(new Message(
            event: '$groupidentify',
            distinctId: sprintf('$%s_%s', $message->groupType, $message->groupKey),
            properties: [
                '$group_type' => $message->groupType,
                '$group_key' => $message->groupKey,
                '$group_set' => $message->groupProperties,
            ],
        ));
    }

    /**
     * @throws \Exception
     */
    public function isFeatureEnabled(string $key, string $distinctId, array $groups = [], array $personProperties = [], array $groupProperties = [], bool $onlyEvaluateLocally = false, bool $sendFeatureFlagEvents = true): bool|null
    {
        return $this->client->isFeatureEnabled($key, $distinctId, $groups, $personProperties, $groupProperties, $onlyEvaluateLocally, $sendFeatureFlagEvents);
    }

    /**
     * @throws \Exception
     */
    public function getFeatureFlag(string $key, string $distinctId, array $groups = [], array $personProperties = [], array $groupProperties = [], bool $onlyEvaluateLocally = false, bool $sendFeatureFlagEvents = true): bool|string|null
    {
        return $this->client->getFeatureFlag($key, $distinctId, $groups, $personProperties, $groupProperties, $onlyEvaluateLocally, $sendFeatureFlagEvents);
    }

    /**
     * @throws \Exception
     */
    public function getAllFlags(string $distinctId, array $groups = [], array $personProperties = [], array $groupProperties = [], bool $onlyEvaluateLocally = false): array
    {
        return $this->client->getAllFlags($distinctId, $groups, $personProperties, $groupProperties, $onlyEvaluateLocally);
    }

    /**
     * @throws \Exception
     */
    public function fetchFeatureVariants(string $distinctId, array $groups = []): array
    {
        return $this->client->fetchFeatureVariants($distinctId, $groups);
    }

    public function alias(AliasMessage $message): bool
    {
        return $this->client->alias($message->toArray());
    }

    public function raw(array $message): mixed
    {
        return $this->client->raw($message);
    }

    public function flush(): bool
    {
        $result = $this->client->flush();

        if (\is_bool($result)) {
            return $result;
        }

        if (\is_string($result)) {
            $decoded = json_decode($result, true);

            if (!\is_array($decoded)) {
                return true;
            }

            return \array_key_exists('status', $decoded);
        }

        return false;
    }
}
