<?php

namespace PhpGuild\ResourceBundle\Normalizer;

use PhpGuild\ResourceBundle\Model\Action\ActionInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * Class ActionDenormalizer
 */
class ActionDenormalizer extends AbstractDenormalizer
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
     * @throws DenormalizerException
     * @throws ExceptionInterface
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $context['actionName'] = $data['name'];

        parent::denormalize($data, $type, $format, $context);

        $this->defaults = $this->defaults['actions'][$data['name']] ?? [];

        if (!isset($data['model']) || !is_a($data['model'], ActionInterface::class, true)) {
            throw new DenormalizerException(sprintf(
                'Action model class "%s" does not implement "%s" interface',
                $data['model'],
                ActionInterface::class
            ), 1003);
        }

        $this->prepareRoute($data);
        $this->prepareFields($data);
        $this->prepareRepository($data);

        return $this->normalizer->denormalize($data, $data['model'], $format, $context);
    }

    /**
     * prepareRoute
     *
     * @param array $data
     */
    private function prepareRoute(array &$data): void
    {
        if (!isset($data['route'])) {
            $data['route'] = [];
        }

        if (!\is_array($data['route'])) {
            $data['route'] = [ 'name' => $data['route'] ];
        }

        if (!isset($data['route']['name'])) {
            $data['route']['name'] = $data['model']::ROUTE_NAME;
            $this->prepareValue($data['route']['name']);
        }

        if (!isset($data['route']['path'])) {
            $data['route']['path'] = $data['model']::ROUTE_PATH;
            $this->prepareValue($data['route']['path']);
        }

        if (!isset($data['route']['methods'])) {
            $data['route']['methods'] = $data['model']::ROUTE_METHODS;
        }

        if (!isset($data['route']['parameters'])) {
            $data['route']['parameters'] = $data['model']::ROUTE_PARAMETERS;
        }

        if (!isset($data['route']['requirements'])) {
            $data['route']['requirements'] = $data['model']::ROUTE_REQUIREMENTS;
        }
    }

    /**
     * prepareFields
     *
     * @param array $data
     */
    private function prepareFields(array &$data): void
    {
        foreach ($this->defaults['fields'] ?? [] as $defaultField) {
            $defaultFieldName = is_array($defaultField) ? $defaultField['name'] : $defaultField;

            $existField = false;
            foreach ($data['fields'] ?? [] as $field) {
                if ($defaultFieldName !== (is_array($field) ? $field['name'] : $field)) {
                    continue;
                }
                $existField = true;
            }

            if ($existField) {
                continue;
            }

            if (!isset($data['fields'])) {
                $data['fields'] = [];
            }

            $data['fields'][] = $defaultField;
        }

        $this->prepareFieldsMetadata($data);
    }

    /**
     * prepareFieldsMetadata
     *
     * @param array $data
     */
    private function prepareFieldsMetadata(array &$data): void
    {
        if (isset($data['fields'])) {
            return;
        }

        $fields = $this->resourceMetaData->getFieldNames();

        // @Todo
        if ('form' === $data['name']) {
            $identifierIndex = array_search($this->resourceMetaData->getSingleIdentifierFieldName(), $fields, true);

            if (false !== $identifierIndex) {
                unset($fields[(string) $identifierIndex]);
            }
        }

        $fields[] = '_actions';

        $data['fields'] = $fields;
    }

    /**
     * prepareRepository
     *
     * @param array $data
     */
    private function prepareRepository(array &$data): void
    {
        if (!isset($data['repository'])) {
            $data['repository'] = [];
        }

        $data['repository']['model'] = $this->resourceMetaData->getName();
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
        return is_a($type, ActionInterface::class, true);
    }
}
