<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Model\Action;

/**
 * Class UpdateAction
 */
class UpdateAction extends FormAction
{
    public const ROUTE_PATH = '{_resource}/{id}/{_action}';
    public const ROUTE_METHODS = [ 'GET', 'PUT' ];
    public const ROUTE_PARAMETERS = [ 'id' => '{resource.id}' ];

    /** @var string $controller */
    protected $controller = \PhpGuild\ResourceBundle\Action\UpdateAction::class;
}
