<?php

namespace PhpGuild\ResourceBundle\Normalizer;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use PhpGuild\ResourceBundle\Model\Field\FieldInterface;
use PhpGuild\ResourceBundle\Model\Type\DefaultType;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Class FieldDenormalizer
 */
class FieldDenormalizer implements ContextAwareDenormalizerInterface
{
    /** @var ObjectNormalizer $normalizer */
    private $normalizer;

    /** @var ClassMetadata $resourceMetaData */
    private $resourceMetaData;

    /** @var array $definitions */
    private $definitions;

    /**
     * FieldDenormalizer constructor.
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
     * @throws ExceptionInterface
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $this->definitions = $context['_definitions'] ?? [];
        $this->resourceMetaData = $context['resourceMetadata'];

        if (!\is_array($data)) {
            $data = [ 'name' => $data ];
        }

        $this->prepareLabel($data, $context);
        $this->prepareType($data);

        return $this->normalizer->denormalize($data, $type, $format, $context);
    }

    /**
     * prepareLabel
     *
     * @param array $data
     * @param array $context
     */
    private function prepareLabel(array &$data, array $context): void
    {
        $data['label'] = $data['label'] ?? sprintf(
            '%s.%s.%s.%s_label',
            $context['contextName'],
            $context['resourceName'],
            Inflector::tableize($data['name']),
            $context['actionName']
        );
    }

    /**
     * prepareType
     *
     * @param array $data
     */
    private function prepareType(array &$data): void
    {
        if ('_actions' === $data['name']) {
            $data['type'] = $data['type'] ?? 'links';

        } elseif ($this->resourceMetaData->hasField($data['name'])) {
            foreach ($this->resourceMetaData->getFieldMapping($data['name']) as $name => $mapping) {
                if (isset($data[$name])) {
                    continue;
                }

                $data[$name] = $mapping;
            }
        }

        $data['type'] = [ $data['type'] ?? null, $data['format'] ?? null ];
        unset($data['format']);
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
        return is_a($type, FieldInterface::class, true);
    }
}
