<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\DependencyInjection\Compiler;

use PhpGuild\ResourceBundle\Handler\ActionModelHandler;
use PhpGuild\ResourceBundle\PhpGuildResourceBundle;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ActionModelPass
 */
class ActionModelPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    /**
     * process
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        $configPasses = $this->findAndSortTaggedServices(PhpGuildResourceBundle::ACTION_MODEL_TAG, $container);
        $definition = $container->getDefinition(ActionModelHandler::class);

        foreach ($configPasses as $service) {
            $definition->addMethodCall('addActionModel', [ $service ]);
        }
    }
}
