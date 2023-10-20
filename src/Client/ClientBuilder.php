<?php

declare(strict_types=1);

namespace PostHog\PostHogBundle\Client;

use PostHog\Client;
use PostHog\HttpClient;
use PostHog\PostHog;
use PostHog\PostHogBundle\Client\Options\Options;

class ClientBuilder implements ClientBuilderInterface
{
    private string $apiKey;
    private Options $options;
    private ?HttpClient $httpClient = null;
    private ?string $personalApiKey = null;

    public function __construct(
        Options $options,
    ) {
        $this->apiKey = (getenv(PostHog::ENV_API_KEY) ?: '');
        $this->options = $options;
    }

    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    public function setOptions(Options $options): void
    {
        $this->options = $options;
    }

    public function setHttpClient(?HttpClient $httpClient): void
    {
        $this->httpClient = $httpClient;
    }

    public function setPersonalApiKey(?string $personalApiKey): void
    {
        $this->personalApiKey = $personalApiKey;
    }

    public function getClient(): Client
    {
        return new Client(
            $this->apiKey,
            $this->options->getOptions(),
            $this->httpClient,
            $this->personalApiKey
        );
    }
}
