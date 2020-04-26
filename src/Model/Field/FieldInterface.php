<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Model\Field;

use PhpGuild\ResourceBundle\Model\Type\TypeInterface;

/**
 * Interface FieldInterface
 */
interface FieldInterface
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
     * @return FieldInterface|self
     */
    public function setName(?string $name): FieldInterface;

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
     * @return FieldInterface|self
     */
    public function setLabel(?string $label): FieldInterface;

    /**
     * getType
     *
     * @return TypeInterface|null
     */
    public function getType(): ?TypeInterface;

    /**
     * setType
     *
     * @param TypeInterface|null $type
     *
     * @return FieldInterface|self
     */
    public function setType(?TypeInterface $type): FieldInterface;
}
