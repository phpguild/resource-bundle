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

//        dump($this->originalConfiguration);

        foreach ($this->originalConfiguration['contexts'] as $contextName => $configuration) {


//            try {
//                /** @var ResourceCollection $debug */
//                $debug = $this->serializer->deserialize(
//                    json_encode($configuration),
//                    $this->resourceCollectionClass,
//                    'json',
//                    array_merge(compact('processorName', 'contextName'), [
//                        '_definitions' => array_replace_recursive(
//                            $this->originalConfiguration['_definitions'] ?? [],
//                            $configuration['_definitions'] ?? []
//                        )
//                    ])
//                );
//                foreach ($debug->getResources()[0]->getActions()[0]->getFields() as $field) {
//                    dump($field);
//                }
//                dump($debug);
//            } catch (\Exception $exception) {
//                dump($exception);
//            }
//            exit;


            $this->collection[$contextName] = $this->cache->get(
                sprintf('%s.configuration.%s', $processorName, $contextName),
                function () use ($processorName, $contextName, $configuration) {
                    return $this->serializer->deserialize(
                        json_encode($configuration),
                        $this->resourceCollectionClass,
                        'json',
                        array_merge(compact('processorName', 'contextName'), [
                            '_definitions' => array_replace_recursive(
                                $this->originalConfiguration['_definitions'] ?? [],
                                $configuration['_definitions'] ?? []
                            )
                        ])
                    );
                }
            );
        }

        $this->builded = true;
    }
}
