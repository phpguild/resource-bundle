<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Model\Type\Link;

use PhpGuild\ResourceBundle\Model\Route;
use PhpGuild\ResourceBundle\Model\Type\TypeInterface;

/**
 * Interface LinkTypeInterface
 */
interface LinkTypeInterface extends TypeInterface
{
    /**
     * getLabel
     *
     * @return string|null
     */
    public function getLabel(): ?string;

    /**
     * setLabel
     *
     * @param string|null $label
     *
     * @return LinkTypeInterface|self
     */
    public function setLabel(?string $label): LinkTypeInterface;

    /**
     * getRoute
     *
     * @return Route|null
     */
    public function getRoute(): ?Route;

    /**
     * setRoute
     *
     * @param Route|null $route
     *
     * @return LinkTypeInterface|self
     */
    public function setRoute(?Route $route): LinkTypeInterface;
}
