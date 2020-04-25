<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Model\Format;

/**
 * Interface FormatCollectionInterface
 */
interface FormatCollectionInterface
{
    /**
     * getCollection
     *
     * @return FormatInterface[]
     */
    public function getCollection(): array;

    /**
     * setCollection
     *
     * @param FormatInterface[] $collection
     *
     * @return FormatCollectionInterface|self
     */
    public function setCollection(array $collection): FormatCollectionInterface;
}
