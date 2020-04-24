<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Model\Action;

/**
 * Class CreateAction
 */
class CreateAction extends FormAction
{
    /** @var string */
    public const ACTION_NAME = 'create';

    /** @var string $controller */
    protected $controller = \PhpGuild\ResourceBundle\Action\CreateAction::class;
}
