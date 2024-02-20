<?php

declare(strict_types=1);

namespace PostHog\PostHogBundle\Tests\Command;

use PostHog\Client;
use PostHog\PostHogBundle\Command\PostHogTestCommand;
use PostHog\PostHogBundle\Tests\BaseTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

use function PostHog\PostHogBundle\init;

class TestPostHogCommandTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        init(
            new Client(
                '',
                [
                    'host' => 'https://app.posthog.com',
                ]
            )
        );
    }

    public function testExecuteSuccessfully(): void
    {
        $commandTester = $this->executeCommand();

        $output = $commandTester->getOutput();

        $this->assertSame('', $commandTester->getDisplay());

        $this->assertSame(Command::SUCCESS, $commandTester->getStatusCode());
    }

    private function executeCommand(): CommandTester
    {
        $command = new PostHogTestCommand();
        $command->setName('posthog:test');

        $application = new Application();
        $application->add($command);

        $command = $application->find('posthog:test');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
        ]);

        return $commandTester;
    }
}
