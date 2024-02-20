<?php

declare(strict_types=1);

namespace PostHog\PostHogBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder('post_hog');

        $rootNode = $builder->getRootNode();

        $rootNode->children()
            ->scalarNode('host')->defaultValue('https://app.posthog.com')->end()
            ->scalarNode('key')->defaultValue(null)->end()
            ->booleanNode('enabled')->defaultValue(false)->end()
            ->scalarNode('user_prefix')->defaultValue('user')->end()
            ->end();

        return $builder;
    }
}
