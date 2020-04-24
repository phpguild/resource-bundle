<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Model\Action;

use PhpGuild\ResourceBundle\Model\Field\FormField;

/**
 * Class FormAction
 */
class FormAction extends AbstractAction
{
    /** @var string */
    public const ACTION_NAME = 'form';

    /** @var FormField[] $fields */
    protected $fields = [];
}
