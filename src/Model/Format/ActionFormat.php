<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Model\Format;

/**
 * Class ActionFormat
 */
class ActionFormat implements ActionFormatInterface
{
    /** @var string|null $label */
    private $label;

    /** @var string|null $route */
    private $route;

    /**
     * getLabel
     *
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * setLabel
     *
     * @param string|null $label
     *
     * @return ActionFormatInterface|self
     */
    public function setLabel(?string $label): ActionFormatInterface
    {
        $this->label = $label;

        return $this;
    }

    /**
     * getRoute
     *
     * @return string|null
     */
    public function getRoute(): ?string
    {
        return $this->route;
    }

    /**
     * setRoute
     *
     * @param string|null $route
     *
     * @return ActionFormatInterface|self
     */
    public function setRoute(?string $route): ActionFormatInterface
    {
        $this->route = $route;

        return $this;
    }
}
