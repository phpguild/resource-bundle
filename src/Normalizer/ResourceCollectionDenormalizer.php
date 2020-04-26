<?php

namespace PhpGuild\ResourceBundle\Normalizer;

use Doctrine\ORM\EntityManagerInterface;
use PhpGuild\ResourceBundle\Model\Resource\ResourceCollectionInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Class ResourceCollectionDenormalizer
 */
class ResourceCollectionDenormalizer implements ContextAwareDenormalizerInterface
{
    /** @var ObjectNormalizer $normalizer */
    private $normalizer;

    /** @var EntityManagerInterface $entityManager */
    private $entityManager;

    /** @var array $defaultDefinitions */
    private $defaultDefinitions;

    /**
     * ResourceCollectionDenormalizer constructor.
     *
     * @param ObjectNormalizer       $normalizer
     * @param EntityManagerInterface $entityManager
     * @param ParameterBagInterface  $parameterBag
     */
    public function __construct(
        ObjectNormalizer $normalizer,
        EntityManagerInterface $entityManager,
        ParameterBagInterface $parameterBag
    ) {
        $this->normalizer = $normalizer;
        $this->entityManager = $entityManager;
        $this->defaultDefinitions = $parameterBag->get('phpguild_resource')['_definitions'];
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
     * @throws ExceptionInterface
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $this->prepareResources($data, $context);

        return $this->normalizer->denormalize($data, $type, $format, array_merge($context, [
            '_definitions' => array_replace_recursive($this->defaultDefinitions, $context['_definitions'] ?? []),
        ]));
    }

    /**
     * prepareResources
     *
     * @param array $data
     * @param array $context
     */
    private function prepareResources(array &$data, array &$context): void
    {
        if (!\count($data['resources'])) {
            foreach ($this->entityManager->getMetadataFactory()->getAllMetadata() as $metadata) {
                if ($metadata->isMappedSuperclass) {
                    continue;
                }

                $data['resources'][$metadata->getName()] = null;
            }
        }

        $context['_defaults'] = [];

        foreach ($data['resources'] as $name => $resource) {
            // Ignore disabled resource
            if (false === $resource) {
                unset($data['resources'][$name]);
                continue;
            }

            // Extract defaults action
            if ('_defaults' === $name) {
                $context['_defaults'] = $data['resources'][$name];
                unset($data['resources'][$name]);
                continue;
            }

            $data['resources'][$name]['model'] = $name;
        }

        $data['resources'] = array_values($data['resources']);
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
        return is_a($type, ResourceCollectionInterface::class, true);
    }
}
