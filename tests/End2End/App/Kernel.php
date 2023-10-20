<?php

declare(strict_types=1);

namespace PostHog\PostHogBundle\Tests\End2End\App;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel as SymfonyKernel;

class Kernel extends SymfonyKernel
{

    public function registerBundles(): iterable
    {
        return [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\MonologBundle\MonologBundle(),
            new \PostHog\PostHogBundle\PostHogBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config.yml');

        if (self::VERSION_ID >= 50000) {
            $loader->load(__DIR__ . '/deprecations_for_5.yml');
        }

        if (self::VERSION_ID >= 60000) {
            $loader->load(__DIR__ . '/deprecations_for_6.yml');
        }
    }
}
