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
        $context = $rootNode
            ->children()
                ->arrayNode('contexts')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->prototype('array')
                        ->children();

                            $context
                            ->arrayNode('resources')
                                ->normalizeKeys(false)
                                ->useAttributeAsKey('name', false)
                                ->defaultValue([])
                                ->prototype('variable')
                            ->end();

                            $this->addResourceConfigurationContext($context);

                            $context
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * addResourceConfigurationContext
     *
     * @param NodeBuilder $context
     */
    public function addResourceConfigurationContext(NodeBuilder $context): void
    {
    }
}
