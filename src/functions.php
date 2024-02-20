<?php

declare(strict_types=1);

namespace PostHog\PostHogBundle;

use PostHog\Client;

function init(?Client $client = null): void
{
    PostHogBundle::initialize($client);
}

function identify(array $message): bool
{
    return PostHogBundle::getInstance()->identify($message);
}
