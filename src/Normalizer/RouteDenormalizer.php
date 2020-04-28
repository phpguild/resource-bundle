<?php

namespace PhpGuild\ResourceBundle\Normalizer;

use PhpGuild\ResourceBundle\Model\RouteInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * Class RouteDenormalizer
 */
class RouteDenormalizer extends AbstractDenormalizer
{
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
     * @throws ExceptionInterface
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        parent::denormalize($data, $type, $format, $context);

        if (!\is_array($data)) {
            $data = [ 'name' => $data ];
        }

        $this->prepareValue($data['name']);

        return $this->denormalizer->denormalize($data, $type, $format, $context);
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
        return parent::supportsDenormalization($data, $type, $format, $context)
            && is_a($type, RouteInterface::class, true);
    }
}
