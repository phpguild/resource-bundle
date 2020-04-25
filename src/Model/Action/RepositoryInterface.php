<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Model\Action;

/**
 * Interface RepositoryInterface
 */
interface RepositoryInterface
{
    /**
     * getModel
     *
     * @return string|null
     */
    public function getModel(): ?string;

    /**
     * setModel
     *
     * @param string|null $model
     *
     * @return RepositoryInterface|self
     */
    public function setModel(?string $model): RepositoryInterface;

    /**
     * getMethod
     *
     * @return string|null
     */
    public function getMethod(): ?string;

    /**
     * setMethod
     *
     * @param string|null $method
     *
     * @return RepositoryInterface|self
     */
    public function setMethod(?string $method): RepositoryInterface;
}
