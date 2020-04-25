<?php

namespace PhpGuild\ResourceBundle\Model\Resource;

use PhpGuild\ResourceBundle\Model\Action\RepositoryInterface;
use PhpGuild\ResourceBundle\Model\Field\FieldInterface;

/**
 * Class ResourceParameters
 */
class ResourceParameters implements ResourceParametersInterface
{
    /** @var RepositoryInterface|null $repository */
    private $repository;

    /** @var FieldInterface[] $fields */
    private $fields;

    /**
     * getRepository
     *
     * @return RepositoryInterface|null
     */
    public function getRepository(): ?RepositoryInterface
    {
        return $this->repository;
    }

    /**
     * setRepository
     *
     * @param RepositoryInterface|null $repository
     *
     * @return ResourceParametersInterface|self
     */
    public function setRepository(?RepositoryInterface $repository): ResourceParametersInterface
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * getFields
     *
     * @return FieldInterface[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * setFields
     *
     * @param FieldInterface[] $fields
     *
     * @return ResourceParametersInterface|self
     */
    public function setFields(array $fields): ResourceParametersInterface
    {
        $this->fields = $fields;

        return $this;
    }
}
