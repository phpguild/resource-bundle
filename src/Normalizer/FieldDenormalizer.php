<?php

namespace PhpGuild\ResourceBundle\Normalizer;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use PhpGuild\ResourceBundle\Model\Field\FieldInterface;
use PhpGuild\ResourceBundle\Model\Format\ActionCollectionFormat;
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
        $this->resourceMetaData = $context['resourceMetadata'];

        if (!\is_array($data)) {
            $data = [ 'name' => $data ];
        }

        $this->prepareLabel($data, $context);
        $this->prepareAction($data);

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
     * prepareAction
     *
     * @param array $data
     */
    private function prepareAction(array &$data): void
    {
        if ('_actions' === $data['name']) {
            $data['type'] = 'action';
            if (!isset($data['format'])) {
                $data['format'] = [
                    ActionCollectionFormat::class,
                    [ [ 'label' => 'toto', 'route' => 'tata' ] ],
                ];
            }

        } elseif ($this->resourceMetaData->hasField($data['name'])) {
            foreach ($this->resourceMetaData->getFieldMapping($data['name']) as $name => $mapping) {
                if (isset($data[$name])) {
                    continue;
                }

                $data[$name] = $mapping;
            }
        }
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
