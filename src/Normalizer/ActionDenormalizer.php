<?php

namespace PhpGuild\ResourceBundle\Normalizer;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use PhpGuild\ResourceBundle\Model\Action\ActionInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Class ActionDenormalizer
 */
class ActionDenormalizer implements ContextAwareDenormalizerInterface
{
    /** @var ObjectNormalizer $normalizer */
    private $normalizer;

    /** @var ClassMetadata $resourceMetaData */
    private $resourceMetaData;

    /** @var array $defaults */
    private $defaults;

    /**
     * ActionDenormalizer constructor.
     *
     * @param ObjectNormalizer   $normalizer
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
        if (!isset($data['model']) || !is_a($data['model'], ActionInterface::class, true)) {
            throw new DenormalizerException(sprintf(
                'Action model class "%s" does not implement "%s" interface',
                $data['model'],
                ActionInterface::class
            ), 1003);
        }

        $this->defaults = $context['_defaults']['actions'][$data['name']] ?? [];
        $this->resourceMetaData = $context['resourceMetadata'];

        $this->prepareRoute($data, $context);
        $this->prepareFields($data);
        $this->prepareRepository($data);

        return $this->normalizer->denormalize($data, $data['model'], $format, array_merge($context, [
            'actionName' => $data['name'],
        ]));
    }

    /**
     * prepareRoute
     *
     * @param array $data
     * @param array $context
     */
    private function prepareRoute(array &$data, array $context): void
    {
        if (!isset($data['route'])) {
            $data['route'] = [];
        }

        if (!isset($data['route']['name'])) {
            $data['route']['name'] = sprintf(
                '%s_%s_%s',
                $context['contextName'],
                $context['resourceName'],
                $data['name']
            );
        }

        if (!isset($data['route']['path'])) {
            $data['route']['path'] = sprintf(
                '%s%s',
                $context['resourceName'],
                ('list' !== $data['name'] ? '/' . $data['name'] : '')
            );
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

        if ('form' === $data['name']) {
            $identifierIndex = array_search($this->resourceMetaData->getSingleIdentifierFieldName(), $fields, true);

            if (false !== $identifierIndex) {
                unset($fields[(string) $identifierIndex]);
            }
        }

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
