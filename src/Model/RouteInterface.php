<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Model;

/**
 * Interface RouteInterface
 */
interface RouteInterface
{
    /**
     * getName
     *
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * setName
     *
     * @param string|null $name
     *
     * @return RouteInterface|self
     */
    public function setName(?string $name): RouteInterface;

    /**
     * getParameters
     *
     * @return array
     */
    public function getParameters(): array;

    /**
     * setParameters
     *
     * @param array $parameters
     *
     * @return RouteInterface|self
     */
    public function setParameters(array $parameters): RouteInterface;

    /**
     * getRequirements
     *
     * @return array
     */
    public function getRequirements(): array;

    /**
     * setRequirements
     *
     * @param array $requirements
     *
     * @return RouteInterface|self
     */
    public function setRequirements(array $requirements): RouteInterface;

    /**
     * getMethods
     *
     * @return array
     */
    public function getMethods(): array;

    /**
     * setMethods
     *
     * @param array $methods
     *
     * @return RouteInterface|self
     */
    public function setMethods(array $methods): RouteInterface;

    /**
     * getPath
     *
     * @return string|null
     */
    public function getPath(): ?string;

    /**
     * setPath
     *
     * @param string|null $path
     *
     * @return RouteInterface|self
     */
    public function setPath(?string $path): RouteInterface;
}
