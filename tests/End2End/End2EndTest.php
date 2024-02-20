<?php

declare(strict_types=1);

namespace PostHog\PostHogBundle\Tests\End2End;

use PostHog\PostHogBundle\Tests\End2End\App\Kernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class End2EndTest extends WebTestCase
{
    protected static function getKernelClass(): string
    {
        return Kernel::class;
    }

    public function testUserFlow(): void
    {
        $client = static::createClient(['debug' => false]);

        $client->request('GET', '/');

        $response = $client->getResponse();

        $this->assertSame(200, $response->getStatusCode());
    }

    public function testCommandExecution(): void
    {
        self::bootKernel();
        $application = new Application(self::$kernel);
        $command = $application->find('posthog:test');

        $tester = new CommandTester($command);

        $result = $tester->execute([]);

        $this->assertSame(0, $tester->getStatusCode());
    }
}
