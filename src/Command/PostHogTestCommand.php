<?php

declare(strict_types=1);

namespace PostHog\PostHogBundle\Command;

use PostHog\PostHogBundle\Model\IdentifyMessage;
use PostHog\PostHogBundle\Model\Message;
use PostHog\PostHogBundle\PostHogBundle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PostHogTestCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $posthog = PostHogBundle::getInstance();

        $output->writeln('Identifying...');
        $whoami = shell_exec('whoami');
        if (!\is_string($whoami)) {
            return self::FAILURE;
        }
        $posthog->identify(new IdentifyMessage($whoami));

        $output->writeln('Sending test message...');

        return $posthog->capture(new Message(
            event: 'test_event',
            distinctId: $whoami,
            properties: [
                'test' => 'test',
            ],
        ))
            ? self::SUCCESS
            : self::FAILURE;
    }
}
