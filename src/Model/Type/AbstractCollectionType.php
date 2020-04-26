<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Model\Type;

/**
 * Class AbstractCollectionType
 */
abstract class AbstractCollectionType extends AbstractType implements TypeCollectionInterface
{
    /** @var TypeInterface[] $collection */
    protected $collection;

    /**
     * getCollection
     *
     * @return TypeInterface[]
     */
    public function getCollection(): array
    {
        return $this->collection;
    }

    /**
     * setCollection
     *
     * @param TypeInterface[] $collection
     *
     * @return TypeCollectionInterface|self
     */
    public function setCollection(array $collection): TypeCollectionInterface
    {
        $this->collection = $collection;

        return $this;
    }
}
