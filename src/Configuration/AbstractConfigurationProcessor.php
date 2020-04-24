<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Configuration;

use Doctrine\Common\Inflector\Inflector;
use PhpGuild\ResourceBundle\Model\Resource\ResourceCollection;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;

/**
 * Class AbstractConfigurationProcessor
 */
abstract class AbstractConfigurationProcessor implements ConfigurationProcessorInterface
{
    /** @var bool $builded */
    private $builded = false;

    /** @var SerializerInterface $serializer */
    private $serializer;

    /** @var CacheInterface $cache */
    private $cache;

    /** @var array $collection */
    private $collection = [];

    /** @var array $originalConfiguration */
    private $originalConfiguration;

    /** @var string $resourceCollectionClass */
    protected $resourceCollectionClass = ResourceCollection::class;

    /**
     * AbstractConfigurationProcessor constructor.
     *
     * @param array               $originalConfiguration
     * @param SerializerInterface $serializer
     * @param CacheInterface      $cache
     */
    public function __construct(
        array $originalConfiguration,
        SerializerInterface $serializer,
        CacheInterface $cache
    ) {
        $this->originalConfiguration = $originalConfiguration;
        $this->serializer = $serializer;
        $this->cache = $cache;
    }

    /**
     * getCollection
     *
     * @return array
     * @throws InvalidArgumentException
     */
    final public function getCollection(): array
    {
        $this->build();

        return $this->collection;
    }

    /**
     * build
     *
     * @throws InvalidArgumentException
     */
    private function build(): void
    {
        if (true === $this->builded) {
            return;
        }

        $processorName = str_replace('\\', '.', Inflector::tableize(get_class($this)));

        foreach ($this->originalConfiguration['contexts'] as $contextName => $configuration) {
            $this->collection[$contextName] = $this->cache->get(
                sprintf('%s.configuration.%s', $processorName, $contextName),
                function () use ($processorName, $contextName, $configuration) {
                    return $this->serializer->deserialize(
                        json_encode($configuration),
                        $this->resourceCollectionClass,
                        'json',
                        compact('processorName', 'contextName')
                    );
                }
            );
        }

        $this->builded = true;
    }
}
