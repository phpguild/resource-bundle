<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Model\Action;

/**
 * Class CreateAction
 */
class CreateAction extends FormAction
{
    public const ROUTE_METHODS = [ 'GET', 'POST' ];

    /** @var string $controller */
    protected $controller = \PhpGuild\ResourceBundle\Action\CreateAction::class;
}
