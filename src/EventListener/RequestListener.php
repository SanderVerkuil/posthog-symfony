<?php

declare(strict_types=1);

namespace PostHog\PostHogBundle\EventListener;

use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class RequestListener
{
    public function __construct()
    {
    }

    public function handleKernelRequestEvent(RequestEvent $event): void
    {
        if (!$this->isMainRequest($event)) {
            return;
        }
    }

    public function handleKernelControllerEvent(ControllerEvent $event): void
    {
        if (!$this->isMainRequest($event)) {
            return;
        }

        $route = $event->getRequest()->attributes->get('_route');

        if (!\is_string($route)) {
            return;
        }
    }

    private function isMainRequest(KernelEvent $event): bool
    {
        return method_exists($event, 'isMainRequest')
            ? $event->isMainRequest()
            : $event->isMasterRequest();
    }
}
