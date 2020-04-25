<?php

namespace PhpGuild\ResourceBundle\Normalizer;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\MappingException;
use PhpGuild\ResourceBundle\Model\Action\ActionInterface;
use PhpGuild\ResourceBundle\Handler\ActionModelHandler;
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

    /** @var ActionModelHandler $actionModelHandler */
    private $actionModelHandler;

    /**
     * ActionDenormalizer constructor.
     *
     * @param ObjectNormalizer   $normalizer
     * @param ActionModelHandler $actionModelHandler
     */
    public function __construct(ObjectNormalizer $normalizer, ActionModelHandler $actionModelHandler)
    {
        $this->normalizer = $normalizer;
        $this->actionModelHandler = $actionModelHandler;
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
     * @throws MappingException
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $actionClassName = $this->actionModelHandler->get($data['name']);

        if (!$actionClassName) {
            throw new DenormalizerException();
        }

        $default = $context['resourceDefaults']['actions'][$data['name']] ?? [];

        /** @var ClassMetadata $resourceMetadata */
        $resourceMetadata = $context['resourceMetadata'];

        $this->prepareRoute($data, $context);
        $this->prepareFields($data, $resourceMetadata, $default);
        $this->prepareRepository($data, $resourceMetadata);

        return $this->normalizer->denormalize($data, get_class($actionClassName), $format, array_merge($context, [
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
     * @param array         $data
     * @param ClassMetadata $resourceMetadata
     * @param array         $default
     *
     * @throws MappingException
     */
    private function prepareFields(array &$data, ClassMetadata $resourceMetadata, array $default): void
    {
        foreach ($default['fields'] ?? [] as $defaultField) {
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

        $this->prepareFieldsMetadata($data, $resourceMetadata);
    }

    /**
     * prepareFieldsMetadata
     *
     * @param array         $data
     * @param ClassMetadata $resourceMetadata
     *
     * @throws MappingException
     */
    private function prepareFieldsMetadata(array &$data, ClassMetadata $resourceMetadata): void
    {
        if (isset($data['fields'])) {
            return;
        }

        $fields = $resourceMetadata->getFieldNames();

        if ('form' === $data['name']) {
            $identifierIndex = array_search($resourceMetadata->getSingleIdentifierFieldName(), $fields, true);

            if (false !== $identifierIndex) {
                unset($fields[$identifierIndex]);
            }
        }

        $data['fields'] = $fields;
    }

    /**
     * prepareRepository
     *
     * @param array         $data
     * @param ClassMetadata $resourceMetadata
     */
    private function prepareRepository(array &$data, ClassMetadata $resourceMetadata): void
    {
        if (!isset($data['repository'])) {
            $data['repository'] = [];
        }

        $data['repository']['model'] = $resourceMetadata->getName();
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
