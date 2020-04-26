<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Model\Type\Link;

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
     * @return LinkTypeInterface|self
     */
    public function setRoute(?string $route): LinkTypeInterface
    {
        $this->route = $route;

        return $this;
    }
}
