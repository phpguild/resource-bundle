<?php

namespace PhpGuild\ResourceBundle\Router;

use PhpGuild\ResourceBundle\Configuration\ConfigurationException;
use PhpGuild\ResourceBundle\Configuration\ConfigurationProcessorInterface;
use PhpGuild\ResourceBundle\Handler\ConfigurationProcessorHandler;
use PhpGuild\ResourceBundle\Model\Action\ActionInterface;
use PhpGuild\ResourceBundle\Model\Resource\ResourceCollectionInterface;
use PhpGuild\ResourceBundle\Model\Resource\ResourceElementInterface;
use PhpGuild\ResourceBundle\Model\Resource\ResourceParameters;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Config\Loader\Loader as AbstractLoader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class Loader
 */
class Loader extends AbstractLoader
{
    /** @var ConfigurationProcessorHandler $configurationProcessorHandler */
    private $configurationProcessorHandler;

    /** @var bool $loaded */
    private $loaded = false;

    /**
     * Loader constructor.
     *
     * @param ConfigurationProcessorHandler $configurationProcessorHandler
     */
    public function __construct(ConfigurationProcessorHandler $configurationProcessorHandler)
    {
        $this->configurationProcessorHandler = $configurationProcessorHandler;
    }

    /**
     * load
     *
     * @param mixed       $resource
     * @param string|null $type
     *
     * @return RouteCollection
     *
     * @throws ConfigurationException
     * @throws InvalidArgumentException
     */
    public function load($resource, string $type = null): RouteCollection
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add this loader twice');
        }

        $routes = new RouteCollection();

        /** @var ConfigurationProcessorInterface $configurationProcessor */
        foreach ($this->configurationProcessorHandler->getCollection() as $configurationProcessor) {
            /** @var ResourceCollectionInterface $resourceCollection */
            foreach ($configurationProcessor->getCollection() as $resourceCollection) {
                /** @var ResourceElementInterface $resourceElement */
                foreach ($resourceCollection->getResources() as $resourceElement) {
                    /** @var ActionInterface $action */
                    foreach ($resourceElement->getActions() as $action) {
                        $actionRoute = $action->getRoute();

                        if (!$actionRoute) {
                            continue;
                        }

                        $resourceParameters = new ResourceParameters();
                        $resourceParameters->setFields($action->getFields());
                        $resourceParameters->setRepository($action->getRepository());

                        $routes->add($actionRoute->getName(), new Route($actionRoute->getPath(), [
                            '_controller' => $action->getController(),
                            '_resourceParameters' => serialize($resourceParameters),
                        ], $actionRoute->getRequirements(), [], null, [], $actionRoute->getMethods()));
                    }
                }
            }
        }

        $this->loaded = true;

        return $routes;
    }

    /**
     * supports
     *
     * @param mixed       $resource
     * @param string|null $type
     *
     * @return bool
     */
    public function supports($resource, string $type = null): bool
    {
        return 'phpguild_resource' === $type;
    }
}
