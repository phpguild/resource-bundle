<?php

namespace PhpGuild\ResourceBundle\Normalizer;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Symfony\Component\Serializer\Exception\BadMethodCallException;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Class AbstractDenormalizer
 */
abstract class AbstractDenormalizer implements ContextAwareDenormalizerInterface
{
    /** @var DenormalizerInterface */
    protected $denormalizer;

    /** @var array $definitions */
    protected $definitions = [];

    /** @var array $defaults */
    protected $defaults = [];

    /** @var ClassMetadata|null $resourceMetaData */
    protected $resourceMetaData;

    /** @var string|null $contextName */
    protected $contextName;

    /** @var string|null $resourceName */
    protected $resourceName;

    /** @var string|null $actionName */
    protected $actionName;

    /** @var string|null $fieldName */
    protected $fieldName;

    /**
     * AbstractDenormalizer constructor.
     *
     * @param ObjectNormalizer $normalizer
     */
    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->denormalizer = $normalizer;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        if (null === $this->denormalizer) {
            throw new BadMethodCallException('Please set a denormalizer before calling denormalize()!');
        }

        $this->definitions = $context['_definitions'] ?? [];
        $this->defaults = $context['_defaults'] ?? [];
        $this->resourceMetaData = $context['resourceMetadata'] ?? null;
        $this->contextName = $context['contextName'] ?? null;
        $this->resourceName = $context['resourceName'] ?? null;
        $this->actionName = $context['actionName'] ?? null;
        $this->fieldName = $context['fieldName'] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, string $type, string $format = null, array $context = []): bool
    {
        if (null === $this->denormalizer) {
            throw new BadMethodCallException(sprintf('The denormalizer needs to be set to allow "%s()" to be used.', __METHOD__));
        }

        return true;
    }

    /**
     * prepareValue
     *
     * @param string $value
     * @param array  $data
     */
    protected function prepareValue(string &$value, array $data = []): void
    {
        $value = str_replace(array_merge([
            '{_context}',
            '{_resource}',
            '{_action}',
            '{_field}',
        ], array_map(static function (string $key) {
            return sprintf('{%s}', rtrim(ltrim($key, '{'), '}'));
        }, array_keys($data))), array_merge([
            $this->contextName,
            $this->resourceName,
            $this->actionName,
            $this->fieldName,
        ], array_values($data)), $value);
    }
}
