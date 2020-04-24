<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Configuration;

use PhpGuild\ResourceBundle\Model\Resource\ResourceCollectionInterface;
use Psr\Cache\InvalidArgumentException;

/**
 * Interface ConfigurationManagerInterface
 */
interface ConfigurationManagerInterface
{
    /**
     * getContext
     *
     * @return string
     * @throws ConfigurationException
     */
    public function getContext(): string;

    /**
     * getConfiguration
     *
     * @return ResourceCollectionInterface
     * @throws ConfigurationException
     * @throws InvalidArgumentException
     */
    public function getConfiguration(): ?ResourceCollectionInterface;
}
