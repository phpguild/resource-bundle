<?php

namespace PhpGuild\ResourceBundle\Normalizer;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\EntityManagerInterface;
use PhpGuild\ResourceBundle\Model\Action\ActionInterface;
use PhpGuild\ResourceBundle\Model\Resource\ResourceElement;
use PhpGuild\ResourceBundle\Model\Resource\ResourceElementInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Class ResourceElementDenormalizer
 */
class ResourceElementDenormalizer extends AbstractDenormalizer
{
    /** @var EntityManagerInterface $entityManager */
    private $entityManager;

    /**
     * ResourceElementDenormalizer constructor.
     *
     * @param ObjectNormalizer       $normalizer
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ObjectNormalizer $normalizer, EntityManagerInterface $entityManager)
    {
        parent::__construct($normalizer);

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
        $context['resourceMetadata'] = $this->entityManager->getClassMetadata($data['model']);

        parent::denormalize($data, $type, $format, $context);

        $this->prepareName($data);
        $this->prepareLabel($data);
        $this->prepareActions($data);

        /** @var ResourceElement $resourceElement */
        $resourceElement = $this->denormalizer->denormalize($data, $type, $format, array_merge($context, [
            'resourceName' => $this->resourceName,
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

        $this->prepareValue($data['name']);

        $this->resourceName = $data['name'];
    }

    /**
     * prepareLabel
     *
     * @param array $data
     */
    private function prepareLabel(array &$data): void
    {
        if (!isset($data['label'])) {
            $data['label'] = '{_context}.{_resource}.label';
        }

        $this->prepareValue($data['label']);
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
        return parent::supportsDenormalization($data, $type, $format, $context)
            && is_a($type, ResourceElementInterface::class, true);
    }
}
