<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Model\Format;

/**
 * Interface ActionFormatInterface
 */
interface ActionFormatInterface
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
     * @return ActionFormatInterface|self
     */
    public function setLabel(?string $label): ActionFormatInterface;

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
     * @return ActionFormatInterface|self
     */
    public function setRoute(?string $route): ActionFormatInterface;
}
