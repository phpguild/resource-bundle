<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Model\Type\Link;

use PhpGuild\ResourceBundle\Model\Route;
use PhpGuild\ResourceBundle\Model\Type\AbstractType;

/**
 * Class LinkType
 */
class LinkType extends AbstractType implements LinkTypeInterface
{
    /** @var string|null $name */
    protected $name = 'link';

    /** @var string|null $label */
    private $label;

    /** @var Route|null $route */
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
     * @return LinkTypeInterface|self
     */
    public function setLabel(?string $label): LinkTypeInterface
    {
        $this->label = $label;

        return $this;
    }

    /**
     * getRoute
     *
     * @return Route|null
     */
    public function getRoute(): ?Route
    {
        return $this->route;
    }

    /**
     * setRoute
     *
     * @param Route|null $route
     *
     * @return LinkTypeInterface|self
     */
    public function setRoute(?Route $route): LinkTypeInterface
    {
        $this->route = $route;

        return $this;
    }
}
