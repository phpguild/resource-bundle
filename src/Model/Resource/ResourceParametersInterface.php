<?php

namespace PhpGuild\ResourceBundle\Model\Resource;

use PhpGuild\ResourceBundle\Model\Action\RepositoryInterface;
use PhpGuild\ResourceBundle\Model\Field\FieldInterface;

/**
 * Interface ResourceParametersInterface
 */
interface ResourceParametersInterface
{
    /**
     * getRepository
     *
     * @return RepositoryInterface|null
     */
    public function getRepository(): ?RepositoryInterface;

    /**
     * setRepository
     *
     * @param RepositoryInterface|null $repository
     *
     * @return ResourceParametersInterface|self
     */
    public function setRepository(?RepositoryInterface $repository): ResourceParametersInterface;

    /**
     * getFields
     *
     * @return FieldInterface[]
     */
    public function getFields(): array;

    /**
     * setFields
     *
     * @param FieldInterface[] $fields
     *
     * @return ResourceParametersInterface|self
     */
    public function setFields(array $fields): ResourceParametersInterface;
}
