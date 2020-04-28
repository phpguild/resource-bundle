<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Tests\DependencyInjection;

use PhpGuild\ResourceBundle\DependencyInjection\ResourceConfigurationTrait;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 */
class Configuration implements ConfigurationInterface
{
    use ResourceConfigurationTrait;

    /**
     * getAlias
     *
     * @return string
     */
    public function getAlias(): string
    {
        return 'phpguild_resource';
    }

    /**
     * getConfigTreeBuilder
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('phpguild_resource');

        $this->addResourceConfiguration($treeBuilder->getRootNode());

        return $treeBuilder;
    }
}
