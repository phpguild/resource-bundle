<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Model\Resource;

/**
 * Interface ResourceCollectionInterface
 */
interface ResourceCollectionInterface
{
    /**
     * getResources
     *
     * @return ResourceElementInterface[]
     */
    public function getResources(): array;

    /**
     * setResources
     *
     * @param ResourceElementInterface[] $resources
     *
     * @return ResourceCollectionInterface|self
     */
    public function setResources(array $resources): ResourceCollectionInterface;
}
