<?php

declare(strict_types=1);

namespace PostHog\PostHogBundle\EventListener;

use PostHog\PostHog;
use PostHog\PostHogBundle\PostHogInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\SwitchUserToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

final class LoginListener
{
    private PostHogInterface $postHog;
    private ?TokenStorageInterface $tokenStorage;

    public function __construct(
        PostHogInterface $postHog,
        ?TokenStorageInterface $tokenStorage
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->postHog = $postHog;
    }

    /**
     * This method is called for each request handled by the framework.
     */
    public function handleKernelRequestEvent(RequestEvent $event): void
    {
        if (null === $this->tokenStorage || !$this->isMainRequest($event)) {
            return;
        }

        $token = $this->tokenStorage->getToken();

        if (null !== $token) {
            $this->updateUserContext($token);
        }
    }

    /**
     * This method is called after authentication was fully successful. It allows
     * to set information like the username of the currently authenticated user
     * and of the impersonator.
     */
    public function handleLoginSuccessEvent(LoginSuccessEvent $event): void
    {
        $this->updateUserContext($event->getAuthenticatedToken());
    }

    /**
     * This method is called when an authentication provider authenticates the
     * user. It is the event closest to {@see LoginSuccessEvent} in versions of
     * the framework where it doesn't exist.
     */
    public function handleAuthenticationSuccessEvent(AuthenticationSuccessEvent $event): void
    {
        $this->updateUserContext($event->getAuthenticationToken());
    }

    private function updateUserContext(TokenInterface $token): void
    {
        if (!$this->isTokenAuthenticated($token)) {
            return;
        }

        $message = [
            'distinctId' => $this->getUserIdentifier($token->getUser()),
        ];

        $impersonatorUser = $this->getImpersonatorUser($token);

        if (null !== $impersonatorUser) {
            $message['$set'] = [
                'impersonator_username' => $impersonatorUser,
            ];
        }

        PostHog::identify($message);
    }

    private function isTokenAuthenticated(TokenInterface $token): bool
    {
        if (method_exists($token, 'isAuthenticated') && !$token->isAuthenticated(false)) {
            return false;
        }

        return null !== $token->getUser();
    }

    private function getUserIdentifier($user): ?string
    {
        if ($user instanceof UserInterface) {
            if (method_exists($user, 'getUserIdentifier')) {
                return $user->getUserIdentifier();
            }

            if (method_exists($user, 'getUsername')) {
                return $user->getUsername();
            }
        }

        if (\is_string($user)) {
            return $user;
        }

        if (\is_object($user) && method_exists($user, '__toString')) {
            return (string) $user;
        }

        return null;
    }

    private function getImpersonatorUser(TokenInterface $token): ?string
    {
        if ($token instanceof SwitchUserToken) {
            return $this->getUserIdentifier($token->getOriginalToken()->getUser());
        }

        return null;
    }

    protected function isMainRequest(KernelEvent $event): bool
    {
        if (method_exists($event, 'isMainRequest')) {
            return $event->isMainRequest();
        }
        if (method_exists($event, 'isMasterRequest')) {
            return $event->isMasterRequest();
        }

        return true;
    }
}
