<?php

namespace PhpGuild\ResourceBundle\Normalizer;

use Doctrine\ORM\EntityManagerInterface;
use PhpGuild\ResourceBundle\Model\Resource\ResourceCollection;
use PhpGuild\ResourceBundle\Model\Resource\ResourceCollectionInterface;
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

    /**
     * ResourceCollectionNormalizer constructor.
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
     * @return array|object
     * @throws ExceptionInterface
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        if (!\count($data['resources'])) {
            foreach ($this->entityManager->getMetadataFactory()->getAllMetadata() as $metadata) {
                if ($metadata->isMappedSuperclass) {
                    continue;
                }

                $data['resources'][$metadata->getName()] = null;
            }
        }

        $resourceDefaults = [];

        foreach ($data['resources'] as $name => $resource) {
            if (false === $resource) {
                unset($data['resources'][$name]);
                continue;
            }

            if ('_defaults' === $name) {
                $resourceDefaults = $data['resources'][$name];
                unset($data['resources'][$name]);
                continue;
            }

            $data['resources'][$name]['model'] = $name;
        }

        $data['resources'] = array_values($data['resources']);

        return $this->normalizer->denormalize($data, $type, $format, array_merge($context, [
            'resourceDefaults' => $resourceDefaults,
        ]));
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
