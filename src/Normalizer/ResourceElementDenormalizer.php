<?php

namespace PhpGuild\ResourceBundle\Normalizer;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use PhpGuild\ResourceBundle\Model\Action\ActionInterface;
use PhpGuild\ResourceBundle\Model\Resource\ResourceElement;
use PhpGuild\ResourceBundle\Model\Resource\ResourceElementInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Class ResourceElementDenormalizer
 */
class ResourceElementDenormalizer implements ContextAwareDenormalizerInterface
{
    /** @var ObjectNormalizer $normalizer */
    private $normalizer;

    /** @var EntityManagerInterface $entityManager */
    private $entityManager;

    /** @var ClassMetadata $resourceMetaData */
    private $resourceMetaData;

    /** @var array $definitions */
    private $definitions;

    /** @var array $defaults */
    private $defaults;

    /**
     * ResourceElementDenormalizer constructor.
     *
     * @param ObjectNormalizer       $normalizer
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        ObjectNormalizer $normalizer,
        EntityManagerInterface $entityManager
    ) {
        $this->normalizer = $normalizer;
        $this->entityManager = $entityManager;
    }

    /**
     * denormalize
     *
     * @param mixed       $data
     * @param string      $type
     * @param string|null $format
     * @param array       $context
     *
     * @return array|object|ResourceElement
     *
     * @throws ExceptionInterface
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $this->definitions = $context['_definitions'] ?? [];
        $this->defaults = $context['_defaults'] ?? [];
        $this->resourceMetaData = $this->entityManager->getClassMetadata($data['model']);

        $this->prepareName($data);
        $this->prepareLabel($data, $context);
        $this->prepareActions($data);

        /** @var ResourceElement $resourceElement */
        $resourceElement = $this->normalizer->denormalize($data, $type, $format, array_merge($context, [
            'resourceName' => $data['name'],
            'resourceMetadata' => $this->resourceMetaData,
        ]));

        /** @var ActionInterface $defaultAction */
        $defaultAction = $resourceElement->getDefaultAction();
        $resourceElement->setPrimaryRoute($defaultAction ? $defaultAction->getRoute() : null);

        return $resourceElement;
    }

    /**
     * prepareName
     *
     * @param array $data
     */
    private function prepareName(array &$data): void
    {
        $data['name'] = Inflector::tableize(substr(
            $this->resourceMetaData->getName(),
            strrpos($this->resourceMetaData->getName(), '\\') + 1
        ));
    }

    /**
     * prepareLabel
     *
     * @param array $data
     * @param array $context
     */
    private function prepareLabel(array &$data, array $context): void
    {
        $data['label'] = $data['label'] ?? sprintf('%s.%s.label', $context['contextName'], $data['name']);
    }

    /**
     * prepareActions
     *
     * @param array $data
     */
    private function prepareActions(array &$data): void
    {
        // If not exist, add default resource actions
        if (isset($this->defaults['actions']) && \is_array($this->defaults['actions'])) {
            foreach ($this->defaults['actions'] as $actionName => $actionConfiguration) {
                if (isset($data['actions'][$actionName])) {
                    continue;
                }

                $data['actions'][$actionName] = $actionConfiguration;
            }
        }

        // If empty actions, add all resource actions from global definitions
        if (!isset($data['actions']) || !\is_array($data['actions'])) {
            $actionNames = array_keys($this->definitions['actions'] ?? []);
            $data['actions'] = array_combine($actionNames, array_map(static function () { return []; }, $actionNames));
        }

        foreach ($data['actions'] as $name => &$action) {
            // Ignore disabled action
            if (false === $action) {
                unset($data['actions'][$name]);
                continue;
            }

            if (!is_array($action)) {
                $action = [];
            }

            $action['name'] = $name;

            // Set model action from global definitions
            if (!isset($action['model'])) {
                $action['model'] = $this->definitions['actions'][$name] ?? null;
            }
        }

        unset($action);

        $data['actions'] = array_values($data['actions']);
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
        return is_a($type, ResourceElementInterface::class, true);
    }
}
