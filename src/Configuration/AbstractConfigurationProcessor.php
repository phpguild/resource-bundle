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
    /** @var SerializerInterface $serializer */
    protected $serializer;

    /** @var CacheInterface $cache */
    protected $cache;

    /** @var bool $builded */
    protected $builded = false;

    /** @var array $collection */
    protected $collection = [];

    /** @var array $originalConfiguration */
    protected $originalConfiguration;

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
    public function getCollection(): array
    {
        $this->build();

        return $this->collection;
    }

    /**
     * build
     *
     * @throws InvalidArgumentException
     */
    protected function build(): void
    {
        if (true === $this->builded) {
            return;
        }

        $processorName = str_replace('\\', '.', Inflector::tableize(get_class($this)));

//        dump($this->originalConfiguration);

        foreach ($this->originalConfiguration['contexts'] as $contextName => $configuration) {


            try {
                /** @var ResourceCollection $debug */
                $debug = $this->deserialize($processorName, $contextName, $configuration);
                foreach ($debug->getResources()[0]->getActions()[0]->getFields() as $field) {
                    dump($field);
                }
                dump($debug);
            } catch (\Exception $exception) {
                dump($exception);
            }
            exit;


            $this->collection[$contextName] = $this->cache->get(
                sprintf('%s.configuration.%s', $processorName, $contextName),
                function () use ($processorName, $contextName, $configuration) {
                    return $this->deserialize($processorName, $contextName, $configuration);
                }
            );
        }

        $this->builded = true;
    }

    /**
     * deserialize
     *
     * @param string $processorName
     * @param string $contextName
     * @param array  $configuration
     *
     * @return array|object
     */
    protected function deserialize(string $processorName, string $contextName, array $configuration)
    {
        $context = compact('processorName', 'contextName');
        $context['_definitions'] = array_replace_recursive(
            $this->originalConfiguration['_definitions'] ?? [],
            $configuration['_definitions'] ?? []
        );

        return $this->serializer->deserialize(
            json_encode($configuration),
            $this->resourceCollectionClass,
            'json',
            $context
        );
    }
}
