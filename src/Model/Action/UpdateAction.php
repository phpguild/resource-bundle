<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Model\Action;

/**
 * Class UpdateAction
 */
class UpdateAction extends FormAction
{
    /** @var string */
    public const ACTION_NAME = 'update';

    /** @var string $controller */
    protected $controller = \PhpGuild\ResourceBundle\Action\UpdateAction::class;
}
