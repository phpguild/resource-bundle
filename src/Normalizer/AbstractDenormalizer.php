<?php

namespace PhpGuild\ResourceBundle\Normalizer;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Symfony\Component\Serializer\Exception\BadMethodCallException;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Class AbstractDenormalizer
 */
abstract class AbstractDenormalizer implements ContextAwareDenormalizerInterface
{
    /** @var ObjectNormalizer $normalizer */
    protected $normalizer;

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
     * @return array|object|void
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        if (null === $this->normalizer) {
            throw new BadMethodCallException('Please set a serializer before calling denormalize()!');
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
