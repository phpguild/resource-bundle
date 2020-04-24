<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Handler;

use PhpGuild\ResourceBundle\Model\Action\ActionInterface;

/**
 * Class ActionModelHandler
 */
final class ActionModelHandler
{
    /** @var ActionInterface[] $collection */
    private $collection = [];

    /**
     * addActionModel
     *
     * @param ActionInterface $action
     */
    public function addActionModel(ActionInterface $action): void
    {
        $this->collection[$action::ACTION_NAME] = $action;
    }

    /**
     * get
     *
     * @param string $name
     *
     * @return ActionInterface|null
     */
    public function get(string $name): ?ActionInterface
    {
        return $this->collection[$name] ?? null;
    }

    /**
     * getActionNames
     *
     * @return array
     */
    public function getActionNames(): array
    {
        return array_keys($this->collection);
    }
}
