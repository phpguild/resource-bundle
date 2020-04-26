<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Model\Type;

/**
 * Interface TypeCollectionInterface
 */
interface TypeCollectionInterface
{
    /**
     * getCollection
     *
     * @return TypeInterface[]
     */
    public function getCollection(): array;

    /**
     * setCollection
     *
     * @param TypeInterface[] $collection
     *
     * @return TypeCollectionInterface|self
     */
    public function setCollection(array $collection): TypeCollectionInterface;
}
