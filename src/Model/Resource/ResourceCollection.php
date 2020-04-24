<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Model\Resource;

/**
 * Class ResourceCollection
 */
class ResourceCollection implements ResourceCollectionInterface
{
    /** @var ResourceElement[] $resources */
    protected $resources = [];

    /**
     * getResources
     *
     * @return ResourceElementInterface[]
     */
    public function getResources(): array
    {
        return $this->resources;
    }

    /**
     * setResources
     *
     * @param ResourceElementInterface[] $resources
     *
     * @return ResourceCollectionInterface|self
     */
    public function setResources(array $resources): ResourceCollectionInterface
    {
        $this->resources = $resources;

        return $this;
    }
}
