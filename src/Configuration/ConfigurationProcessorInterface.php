<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Configuration;

use Psr\Cache\InvalidArgumentException;

/**
 * Interface ConfigurationProcessorInterface
 */
interface ConfigurationProcessorInterface
{
    /**
     * getCollection
     *
     * @return array
     * @throws ConfigurationException
     * @throws InvalidArgumentException
     */
    public function getCollection(): array;
}
