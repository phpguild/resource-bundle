<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Model\Type;

/**
 * Interface TypeInterface
 */
interface TypeInterface
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
     * @return TypeInterface|self
     */
    public function setName(?string $name): TypeInterface;
}
