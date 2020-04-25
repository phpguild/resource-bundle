<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Model\Field;

use PhpGuild\ResourceBundle\Model\Format\FormatInterface;

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
     * getType
     *
     * @return string|null
     */
    public function getType(): ?string;

    /**
     * setType
     *
     * @param string|null $type
     *
     * @return FieldInterface|self
     */
    public function setType(?string $type): FieldInterface;

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
     * getFormat
     *
     * @return FormatInterface|string|null
     */
    public function getFormat();

    /**
     * setFormat
     *
     * @param FormatInterface|string|null $format
     *
     * @return FieldInterface|self
     */
    public function setFormat($format): FieldInterface;
}
