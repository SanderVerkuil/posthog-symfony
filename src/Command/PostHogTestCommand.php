<?php

declare(strict_types=1);

namespace PostHog\PostHogBundle\Command;

use PostHog\PostHogBundle\Adapter\PostHogAdapter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PostHogTestCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $posthog = PostHogAdapter::getInstance();

        $output->writeln('Identifying...');
        $whoami = shell_exec('whoami');
        $posthog->identify(['distinctId' => $whoami]);

        $output->writeln('Sending test message...');

        $captured = $posthog->capture([
            'distinctId' => $whoami,
            'event' => 'test-event'
        ])
            ? self::SUCCESS
            : self::FAILURE;

        return $captured;
    }
}
