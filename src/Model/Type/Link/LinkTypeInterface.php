<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Model\Type\Link;

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
     * @return string|null
     */
    public function getRoute(): ?string;

    /**
     * setRoute
     *
     * @param string|null $route
     *
     * @return LinkTypeInterface|self
     */
    public function setRoute(?string $route): LinkTypeInterface;
}
