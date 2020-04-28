<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Tests\Service;

use Doctrine\Common\Inflector\Inflector;
use PhpGuild\ResourceBundle\Configuration\AbstractConfigurationProcessor;

/**
 * Class ConfigurationProcessor
 */
class ConfigurationProcessor extends AbstractConfigurationProcessor
{
    /**
     * build
     */
    protected function build(): void
    {
        if (true === $this->builded) {
            return;
        }

        $processorName = str_replace('\\', '.', Inflector::tableize(get_class($this)));

        foreach ($this->originalConfiguration as $index => $configurations) {
            $this->collection[$index] = [];

            foreach ($configurations['contexts'] as $contextName => $configuration) {
                $this->collection[$index][$contextName] = $this->deserialize($processorName, $contextName, $configuration ?? []);
            }
        }

        $this->builded = true;
    }
}
