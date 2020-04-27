<?php

namespace PhpGuild\ResourceBundle\Normalizer;

use Doctrine\Common\Inflector\Inflector;
use PhpGuild\ResourceBundle\Model\Field\FieldInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * Class FieldDenormalizer
 */
class FieldDenormalizer extends AbstractDenormalizer
{
    /**
     * denormalize
     *
     * @param mixed       $data
     * @param string      $type
     * @param string|null $format
     * @param array       $context
     *
     * @return array|object|void
     *
     * @throws ExceptionInterface
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        if (!\is_array($data)) {
            $data = [ 'name' => $data ];
        }

        $context['fieldName'] = Inflector::tableize($data['name']);

        parent::denormalize($data, $type, $format, $context);

        $this->prepareType($data);

        return $this->normalizer->denormalize($data, $type, $format, $context);
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

            if (!isset($data['label'])) {
                $data['label'] = '{_context}.ui.actions';
            }

            if (!isset($data['format'])) {
                $data['format'] = [
                    [ 'label' => '{_context}.ui.update', 'action' => 'update' ],
                    [ 'label' => '{_context}.ui.delete', 'action' => 'delete' ],
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

        $data['type'] = [ $data['type'] ?? null, $data['format'] ?? null ];
        unset($data['format']);

        $this->prepareLabel($data);
    }

    /**
     * prepareLabel
     *
     * @param array $data
     */
    private function prepareLabel(array &$data): void
    {
        if (!isset($data['label'])) {
            $data['label'] = '{_context}.{_resource}.{_field}.{_action}_label';
        }

        $this->prepareValue($data['label']);
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
