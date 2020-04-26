<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Model\Type;

/**
 * Class DefaultType
 */
class DefaultType extends AbstractType
{
    /** @var string|null $format */
    private $format;

    /**
     * getFormat
     *
     * @return string|null
     */
    public function getFormat(): ?string
    {
        return $this->format;
    }

    /**
     * setFormat
     *
     * @param string|null $format
     *
     * @return $this
     */
    public function setFormat(?string $format): self
    {
        $this->format = $format;

        return $this;
    }
}
