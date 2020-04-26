<?php

namespace PhpGuild\ResourceBundle\Normalizer;

use PhpGuild\ResourceBundle\Model\Type\DefaultType;
use PhpGuild\ResourceBundle\Model\Type\TypeCollectionInterface;
use PhpGuild\ResourceBundle\Model\Type\TypeInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Class TypeDenormalizer
 */
class TypeDenormalizer implements ContextAwareDenormalizerInterface
{
    /** @var ObjectNormalizer $normalizer */
    private $normalizer;

    /** @var array $definitions */
    private $definitions;

    /**
     * FieldNormalizer constructor.
     *
     * @param ObjectNormalizer $normalizer
     */
    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * denormalize
     *
     * @param mixed       $data
     * @param string      $type
     * @param string|null $format
     * @param array       $context
     *
     * @return array|object
     *
     * @throws DenormalizerException
     * @throws ExceptionInterface
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $this->definitions = $context['_definitions'] ?? [];

        $this->prepareType($data, $type);

        return $this->normalizer->denormalize($data, $type, $format, $context);
    }

    /**
     * prepareType
     *
     * @param array $data
     * @param       $type
     *
     * @throws DenormalizerException
     */
    private function prepareType(array &$data, string &$type): void
    {
        if ($type !== TypeInterface::class) {
            return;
        }

        [ $type, $data ] = $data;

        $type = $this->definitions['types'][$type] ?? $type;

        if (is_a($type, TypeCollectionInterface::class, true)) {
            $data = [ 'collection' => $data ];
        }

        if (is_a($type, TypeInterface::class, true)) {
            return;
        }

        if (null !== $data && !\is_string($data)) {
            throw new DenormalizerException(sprintf(
                '%s::setFormat() require null or string value, %s given.',
                DefaultType::class,
                gettype($data)
            ), 1004);
        }

        $data = [ 'name' => $type, 'format' => $data ];
        $type = DefaultType::class;
    }

    /**
     * supportsDenormalization
     *
     * @param mixed       $data
     * @param string      $type
     * @param string|null $format
     * @param array       $context
     *
     * @return bool
     */
    public function supportsDenormalization($data, string $type, string $format = null, array $context = []): bool
    {
        return is_a($type, TypeInterface::class, true);
    }
}
