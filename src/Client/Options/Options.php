<?php

declare(strict_types=1);

namespace PostHog\PostHogBundle\Client\Options;

class Options
{
    private string $consumer = 'lib_curl';
    private string $host = 'app.posthog.com';
    private bool $ssl = true;
    private int $maximumBackoffDuration = 10000;
    private bool $debug = false;
    private int $timeout = 10000;

    private array $consumerOptions = [];

    public function getOptions(): array
    {
        $options = $this->consumerOptions;
        $options['consumer'] = $this->consumer;
        $options['host'] = $this->host;
        $options['ssl'] = $this->ssl;
        $options['maximum_backoff_duration'] = $this->maximumBackoffDuration;
        $options['debug'] = $this->debug;
        $options['timeout'] = $this->timeout;

        return $options;
    }
}
