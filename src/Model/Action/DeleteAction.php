<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Model\Action;

/**
 * Class DeleteAction
 */
class DeleteAction extends AbstractAction
{
    public const ROUTE_PATH = '{_resource}/{id}/{_action}';
    public const ROUTE_METHODS = [ 'DELETE' ];

    /** @var string $controller */
    protected $controller = \PhpGuild\ResourceBundle\Action\DeleteAction::class;
}
