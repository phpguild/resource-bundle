<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;

/**
 * Trait ResourceConfigurationTrait
 */
trait ResourceConfigurationTrait
{
    /**
     * addResourceConfiguration
     *
     * @param ArrayNodeDefinition $rootNode
     */
    protected function addResourceConfiguration(ArrayNodeDefinition $rootNode): void
    {
        $nodeBuilder = $rootNode->children();

        $this->addContexts($nodeBuilder);
        $this->addDefinitions($nodeBuilder);

        $nodeBuilder->end();
    }

    /**
     * addContexts
     *
     * @param NodeBuilder $nodeBuilder
     */
    private function addContexts(NodeBuilder $nodeBuilder): void
    {
        $node = $nodeBuilder
            ->arrayNode('contexts')
                ->isRequired()
                ->cannotBeEmpty()
                ->arrayPrototype()
                    ->children();

                        $this->addResources($node);
                        $this->addDefinitions($node);
                        $this->addResourceConfigurationContext($node);

                    $node
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * addResources
     *
     * @param NodeBuilder $nodeBuilder
     */
    private function addResources(NodeBuilder $nodeBuilder): void
    {
        $nodeBuilder
            ->arrayNode('resources')
                ->useAttributeAsKey('name', false)
                ->cannotBeEmpty()
                ->defaultValue([])
                ->prototype('variable')
            ->end()
        ;
    }

    /**
     * addDefinitions
     *
     * @param NodeBuilder $nodeBuilder
     */
    private function addDefinitions(NodeBuilder $nodeBuilder): void
    {
        $nodeBuilder
            ->arrayNode('_definitions')
            ->children()
                ->arrayNode('actions')
                    ->children()
                        ->scalarNode('list')->end()
                        ->scalarNode('create')->end()
                        ->scalarNode('update')->end()
                        ->scalarNode('form')->end()


    //                                ->useAttributeAsKey('name', false)
    //                                ->cannotBeEmpty()
    //                                ->defaultValue([])
    //                                ->prototype('variable')
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * addResourceConfigurationContext
     *
     * @param NodeBuilder $nodeBuilder
     */
    private function addResourceConfigurationContext(NodeBuilder $nodeBuilder): void
    {
    }
}
