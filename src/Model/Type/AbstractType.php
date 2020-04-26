<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Model\Type;

/**
 * Class AbstractType
 */
abstract class AbstractType implements TypeInterface
{
    /** @var string|null $name */
    protected $name;

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
     * @return TypeInterface|self
     */
    public function setName(?string $name): TypeInterface
    {
        $this->name = $name;

        return $this;
    }
}
