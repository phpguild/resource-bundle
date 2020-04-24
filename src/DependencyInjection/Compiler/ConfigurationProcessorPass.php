<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\DependencyInjection\Compiler;

use PhpGuild\ResourceBundle\Handler\ConfigurationProcessorHandler;
use PhpGuild\ResourceBundle\PhpGuildResourceBundle;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ConfigurationProcessorPass
 */
class ConfigurationProcessorPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    /**
     * process
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        $configPasses = $this->findAndSortTaggedServices(PhpGuildResourceBundle::CONFIGURATION_PROCESSOR_TAG, $container);
        $definition = $container->getDefinition(ConfigurationProcessorHandler::class);

        foreach ($configPasses as $service) {
            $definition->addMethodCall('addConfigurationProcessor', [ $service ]);
        }
    }
}
