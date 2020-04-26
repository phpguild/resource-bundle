<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Model\Field;

use PhpGuild\ResourceBundle\Model\Type\TypeInterface;

/**
 * Class AbstractField
 */
abstract class AbstractField implements FieldInterface
{
    /** @var string|null $name */
    protected $name;

    /** @var string|null $label */
    protected $label;

    /** @var TypeInterface|null $type */
    protected $type;

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
     * @return FieldInterface|self
     */
    public function setName(?string $name): FieldInterface
    {
        $this->name = $name;

        return $this;
    }

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
     * @return FieldInterface|self
     */
    public function setLabel(?string $label): FieldInterface
    {
        $this->label = $label;

        return $this;
    }

    /**
     * getType
     *
     * @return TypeInterface|null
     */
    public function getType(): ?TypeInterface
    {
        return $this->type;
    }

    /**
     * setType
     *
     * @param TypeInterface|null $type
     *
     * @return FieldInterface|self
     */
    public function setType(?TypeInterface $type): FieldInterface
    {
        $this->type = $type;

        return $this;
    }
}
