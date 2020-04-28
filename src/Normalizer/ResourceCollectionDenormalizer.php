<?php

namespace PhpGuild\ResourceBundle\Normalizer;

use Doctrine\ORM\EntityManagerInterface;
use PhpGuild\ResourceBundle\Model\Resource\ResourceCollectionInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Class ResourceCollectionDenormalizer
 */
class ResourceCollectionDenormalizer extends AbstractDenormalizer
{
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
        parent::__construct($normalizer);

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
        $context['_definitions'] = array_replace_recursive($this->defaultDefinitions, $context['_definitions'] ?? []);

        parent::denormalize($data, $type, $format, $context);

        $this->prepareResources($data, $context);

        return $this->denormalizer->denormalize($data, $type, $format, $context);
    }

    /**
     * prepareResources
     *
     * @param array $data
     * @param array $context
     */
    private function prepareResources(array &$data, array &$context): void
    {
        if (!isset($data['resources']) || !\count($data['resources'])) {
            $data['resources'] = [];

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
        return parent::supportsDenormalization($data, $type, $format, $context)
            && is_a($type, ResourceCollectionInterface::class, true);
    }
}
