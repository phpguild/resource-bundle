<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Model\Type\Link;

use PhpGuild\ResourceBundle\Model\Type\AbstractCollectionType;

/**
 * Class LinkCollectionType
 */
class LinkCollectionType extends AbstractCollectionType
{
    /** @var string|null $name */
    protected $name = 'links';

    /** @var LinkType[] $collection */
    protected $collection;
}
