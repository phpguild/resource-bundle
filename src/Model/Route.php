<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Model;

/**
 * Class Route
 */
class Route implements RouteInterface
{
    /** @var string|null $name */
    private $name;

    /** @var array $parameters */
    private $parameters = [];

    /** @var array $requirements */
    private $requirements = [];

    /** @var array $methods */
    private $methods = [];

    /** @var string|null $path */
    private $path;

    /**
     * getName
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * setName
     *
     * @param string|null $name
     *
     * @return RouteInterface|self
     */
    public function setName(?string $name): RouteInterface
    {
        $this->name = $name;

        return $this;
    }

    /**
     * getParameters
     *
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * setParameters
     *
     * @param array $parameters
     *
     * @return RouteInterface|self
     */
    public function setParameters(array $parameters): RouteInterface
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * getRequirements
     *
     * @return array
     */
    public function getRequirements(): array
    {
        return $this->requirements;
    }

    /**
     * setRequirements
     *
     * @param array $requirements
     *
     * @return RouteInterface|self
     */
    public function setRequirements(array $requirements): RouteInterface
    {
        $this->requirements = $requirements;

        return $this;
    }

    /**
     * getMethods
     *
     * @return array
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * setMethods
     *
     * @param array $methods
     *
     * @return RouteInterface|self
     */
    public function setMethods(array $methods): RouteInterface
    {
        $this->methods = $methods;

        return $this;
    }

    /**
     * getPath
     *
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * setPath
     *
     * @param string|null $path
     *
     * @return RouteInterface|self
     */
    public function setPath(?string $path): RouteInterface
    {
        $this->path = $path;

        return $this;
    }
}
