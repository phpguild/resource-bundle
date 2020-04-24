<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Handler;

use PhpGuild\ResourceBundle\Configuration\ConfigurationProcessorInterface;

/**
 * Class ConfigurationProcessorHandler
 */
final class ConfigurationProcessorHandler
{
    /** @var ConfigurationProcessorInterface[] $collection */
    private $collection = [];

    /**
     * addConfigurationProcessor
     *
     * @param ConfigurationProcessorInterface $configurationProcessor
     */
    public function addConfigurationProcessor($configurationProcessor): void
    {
        $this->collection[] = $configurationProcessor;
    }

    /**
     * getCollection
     *
     * @return ConfigurationProcessorInterface[]
     */
    public function getCollection(): array
    {
        return $this->collection;
    }
}
