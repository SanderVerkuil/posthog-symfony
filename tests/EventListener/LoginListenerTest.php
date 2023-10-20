<?php

declare(strict_types=1);

namespace PostHog\PostHogBundle\Tests\EventListener;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PostHog\PostHogBundle\EventListener\LoginListener;
use PostHog\PostHogBundle\PostHogInterface;
use PostHog\PostHogBundle\Tests\EventListener\Fixtures\UserWithIdentifierStub;
use PostHog\PostHogBundle\UserDataBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\SwitchUserToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginListenerTest extends TestCase
{
    /**
     * @var MockObject&PostHogInterface
     */
    private $postHog;

    /**
     * @var MockObject&TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var LoginListener
     */
    private $listener;

    protected function setUp(): void
    {
        $this->postHog = $this->createMock(PostHogInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->listener = new LoginListener($this->postHog, $this->tokenStorage);
    }

    /**
     * @dataProvider authenticationTokenDataProvider
     */
    public function testHandleLoginSuccessEvent(TokenInterface $token, ?UserDataBag $user, ?UserDataBag $expectedUser): void
    {
        if (!class_exists(LoginSuccessEvent::class)) {
            $this->markTestSkipped('This test is incompatible with versions of Symfony where the LoginSuccessEvent event does not exist.');
        }

        $this->listener->handleLoginSuccessEvent(new LoginSuccessEvent(
            $this->createMock(AuthenticatorInterface::class),
            new SelfValidatingPassport(new UserBadge('foo_passport_user')),
            $token,
            new Request(),
            null,
            'main'
        ));
    }

    public function authenticationTokenDataProvider(): \Generator
    {
        yield 'If the username is already set on the User context, then it is not overridden' => [
            new AuthenticatedTokenStub(new UserWithIdentifierStub()),
            new UserDataBag('bar_user'),
            new UserDataBag('bar_user'),
        ];

        yield 'If the username is not set on the User context, then it is retrieved from the token' => [
            new AuthenticatedTokenStub(new UserWithIdentifierStub()),
            null,
            new UserDataBag('foo_user'),
        ];

        yield 'If the user is being impersonated, then the username of the impersonator is set on the User context' => [
            (static function (): SwitchUserToken {
                if (version_compare(Kernel::VERSION, '5.0.0', '<')) {
                    return new SwitchUserToken(
                        new UserWithIdentifierStub(),
                        null,
                        'foo_provider',
                        ['ROLE_USER'],
                        new AuthenticatedTokenStub(new UserWithIdentifierStub('bar_user'))
                    );
                }

                return new SwitchUserToken(
                    new UserWithIdentifierStub(),
                    'main',
                    ['ROLE_USER'],
                    new AuthenticatedTokenStub(new UserWithIdentifierStub('bar_user'))
                );
            })(),
            null,
            UserDataBag::createFromArray([
                'id' => 'foo_user',
                'impersonator_username' => 'bar_user',
            ]),
        ];
    }
}

final class AuthenticatedTokenStub extends AbstractToken
{
    /**
     * @param UserInterface|\Stringable|string|null $user
     */
    public function __construct($user)
    {
        parent::__construct();

        if (null !== $user) {
            $this->setUser($user);
        }

        if (method_exists($this, 'setAuthenticated')) {
            $this->setAuthenticated(true);
        }
    }

    public function getCredentials(): ?string
    {
        return null;
    }
}
