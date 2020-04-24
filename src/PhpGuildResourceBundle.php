<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle;

use PhpGuild\ResourceBundle\Configuration\ConfigurationProcessorInterface;
use PhpGuild\ResourceBundle\DependencyInjection\Compiler\ConfigurationProcessorPass;
use PhpGuild\ResourceBundle\DependencyInjection\PhpGuildResourceExtension;
use PhpGuild\ResourceBundle\Model\Action\ActionInterface;
use PhpGuild\ResourceBundle\DependencyInjection\Compiler\ActionModelPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class PhpGuildResourceBundle
 */
class PhpGuildResourceBundle extends Bundle
{
    public const ACTION_MODEL_TAG = 'phpguild_resource.action_model';
    public const CONFIGURATION_PROCESSOR_TAG = 'phpguild_resource.configuration_processor';

    /**
     * build
     *
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container
            ->registerForAutoconfiguration(ActionInterface::class)
            ->addTag(self::ACTION_MODEL_TAG)
        ;
        $container->addCompilerPass(new ActionModelPass());

        $container
            ->registerForAutoconfiguration(ConfigurationProcessorInterface::class)
            ->addTag(self::CONFIGURATION_PROCESSOR_TAG)
        ;
        $container->addCompilerPass(new ConfigurationProcessorPass());
    }

    /**
     * getContainerExtension
     *
     * @return ExtensionInterface
     */
    public function getContainerExtension(): ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new PhpGuildResourceExtension();
        }

        return $this->extension;
    }
}
