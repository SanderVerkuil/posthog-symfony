<?php

declare(strict_types=1);

namespace PostHog\PostHogBundle\DependencyInjection;

use PostHog\Client;
use PostHog\PostHogBundle\Client\ClientBuilder;
use PostHog\PostHogBundle\PostHogBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class PostHogExtension extends ConfigurableExtension
{
    public function getNamespace()
    {
        return 'https://posthog.com/schema/dic/posthog-symfony';
    }

    public function getXsdValidationBasePath(): string
    {
        return __DIR__ . '/../../config/schema/posthog-1.0.xsd';
    }

    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.xml');

        $this->registerConfiguration($container, $mergedConfig);
    }

    /**
     * @param array<string, mixed> $config
     */
    private function registerConfiguration(ContainerBuilder $container, array $config): void
    {
        $options = $config['options'];

        $clientBuilderDefinition = (new Definition(ClientBuilder::class))
            ->setArgument(0, new Reference('post_hog.client.options'))
            ->addMethodCall('setSdkIdentifier', [PostHogBundle::SDK_IDENTIFIER])
            ->addMethodCall('setSdkVersion', [PostHogBundle::SDK_VERSION])
            ->addMethodCall('setApiKey', [$options['key']])
            ->addMethodCall('setOptions', [new Reference()])
        ;

        $container
            ->setDefinition('post_hog.client', new Definition(Client::class))
            ->setPublic(false)
            ->setFactory([$clientBuilderDefinition, 'getClient']);
    }
}
