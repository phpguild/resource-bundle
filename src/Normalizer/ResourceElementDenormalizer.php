<?php

namespace PhpGuild\ResourceBundle\Normalizer;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use PhpGuild\ResourceBundle\Handler\ActionModelHandler;
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

    /** @var ActionModelHandler $actionModelHandler */
    private $actionModelHandler;

    /**
     * ResourceElementDenormalizer constructor.
     *
     * @param ObjectNormalizer       $normalizer
     * @param EntityManagerInterface $entityManager
     * @param ActionModelHandler     $actionModelHandler
     */
    public function __construct(
        ObjectNormalizer $normalizer,
        EntityManagerInterface $entityManager,
        ActionModelHandler $actionModelHandler
    ) {
        $this->normalizer = $normalizer;
        $this->entityManager = $entityManager;
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
     * @return array|object|ResourceElement
     * @throws ExceptionInterface
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $resourceMetaData = $this->entityManager->getClassMetadata($data['model']);

        $this->prepareName($data, $resourceMetaData);
        $this->prepareLabel($data, $context);
        $this->prepareActions($data);

        /** @var ResourceElement $resourceElement */
        $resourceElement = $this->normalizer->denormalize($data, $type, $format, array_merge($context, [
            'resourceName' => $data['name'],
            'resourceMetadata' => $resourceMetaData,
        ]));

        /** @var ActionInterface $defaultAction */
        $defaultAction = $resourceElement->getDefaultAction();
        $resourceElement->setPrimaryRoute($defaultAction ? $defaultAction->getRoute() : null);

        return $resourceElement;
    }

    /**
     * prepareName
     *
     * @param array         $data
     * @param ClassMetadata $resourceMetaData
     */
    private function prepareName(array &$data, ClassMetadata $resourceMetaData): void
    {
        $data['name'] = Inflector::tableize(substr(
            $resourceMetaData->getName(),
            strrpos($resourceMetaData->getName(), '\\') + 1
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
        if (!isset($data['actions']) || !\is_array($data['actions'])) {
            $actionNames = $this->actionModelHandler->getActionNames();
            $data['actions'] = array_combine($actionNames, array_map(static function () {
                return [];
            }, $actionNames));
        }

        foreach ($data['actions'] as $name => &$action) {
            if (false === $action) {
                unset($data['actions'][$name]);
                continue;
            }

            if (!is_array($action)) {
                $action = [];
            }

            $action['name'] = $name;
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
