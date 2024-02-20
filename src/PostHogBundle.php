<?php

declare(strict_types=1);

namespace PostHog\PostHogBundle;

use PostHog\Client;
use PostHog\PostHogBundle\Adapter\PostHogAdapter;
use PostHog\PostHogBundle\DependencyInjection\Compiler\AddLoginListenerTagPass;
use PostHog\PostHogBundle\Exception\NotInitializedException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class PostHogBundle extends Bundle
{
    public const SDK_IDENTIFIER = 'posthog.php.symfony';

    public const SDK_VERSION = '0.0.1';

    private static ?PostHogAdapter $instance = null;

    /**
     * @throws NotInitializedException
     */
    public static function getInstance(): PostHogAdapter
    {
        if (null === self::$instance) {
            throw new NotInitializedException();
        }

        return self::$instance;
    }

    public static function initialize(?Client $client = null): void
    {
        self::$instance = new PostHogAdapter($client ?? new Client(
            getenv(\PostHog\PostHog::ENV_API_KEY) ?: '',
            ['host' => getenv(\PostHog\PostHog::ENV_HOST) ?: '']
        ));
    }

    public function boot(): void
    {
        $client = null;
        if (null !== $this->container && $this->container->has('post_hog.client')) {
            $client = $this->container->get('post_hog.client');
            if (!$client instanceof Client) {
                $client = null;
            }
        }
        self::initialize($client);
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new AddLoginListenerTagPass());
    }
}
