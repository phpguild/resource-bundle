<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Model\Format;

/**
 * Class ActionCollectionFormat
 */
class ActionCollectionFormat implements FormatCollectionInterface
{
    /** @var ActionFormat[] $collection */
    private $collection;

    /**
     * getCollection
     *
     * @return ActionFormat[]
     */
    public function getCollection(): array
    {
        return $this->collection;
    }

    /**
     * setCollection
     *
     * @param ActionFormat[] $collection
     *
     * @return FormatCollectionInterface|self
     */
    public function setCollection(array $collection): FormatCollectionInterface
    {
        $this->collection = $collection;

        return $this;
    }
}
