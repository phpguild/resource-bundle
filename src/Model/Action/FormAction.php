<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Model\Action;

use PhpGuild\ResourceBundle\Model\Field\FormField;

/**
 * Class FormAction
 */
class FormAction extends AbstractAction
{
    /** @var FormField[] $fields */
    protected $fields = [];
}
