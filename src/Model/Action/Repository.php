<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Model\Action;

/**
 * Class Repository
 */
class Repository implements RepositoryInterface
{
    /** @var string|null $model */
    private $model;

    /** @var string|null $method */
    private $method = 'findAll';

    /**
     * getModel
     *
     * @return string|null
     */
    public function getModel(): ?string
    {
        return $this->model;
    }

    /**
     * setModel
     *
     * @param string|null $model
     *
     * @return RepositoryInterface|self
     */
    public function setModel(?string $model): RepositoryInterface
    {
        $this->model = $model;

        return $this;
    }

    /**
     * getMethod
     *
     * @return string|null
     */
    public function getMethod(): ?string
    {
        return $this->method;
    }

    /**
     * setMethod
     *
     * @param string|null $method
     *
     * @return RepositoryInterface|self
     */
    public function setMethod(?string $method): RepositoryInterface
    {
        $this->method = $method;

        return $this;
    }
}
