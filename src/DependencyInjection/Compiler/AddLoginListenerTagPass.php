<?php

declare(strict_types=1);

namespace PostHog\PostHogBundle\DependencyInjection\Compiler;

use PostHog\PostHogBundle\EventListener\LoginListener;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Security\Core\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class AddLoginListenerTagPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $listenerDefinition = $container->getDefinition(LoginListener::class);

        if (!class_exists(LoginSuccessEvent::class)) {
            $listenerDefinition->addTag(
                'kernel.event_listener',
                [
                    'event' => AuthenticationSuccessEvent::class,
                    'method' => 'handleAuthenticationSuccessEvent',
                ]
            );
        }
    }
}
