<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Model\Action;

use PhpGuild\ResourceBundle\Model\Field\ListField;

/**
 * Class ListAction
 */
class ListAction extends AbstractAction
{
    public const ROUTE_PATH = '{_resource}';

    /** @var string $controller */
    protected $controller = \PhpGuild\ResourceBundle\Action\ListAction::class;

    /** @var ListField[] $fields */
    protected $fields = [];
}
