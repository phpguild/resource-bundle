<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Configuration;

use PhpGuild\ResourceBundle\Model\Resource\ResourceCollectionInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class AbstractConfigurationManager
 */
abstract class AbstractConfigurationManager implements ConfigurationManagerInterface
{
    /** @var FirewallMap $firewallMap */
    private $firewallMap;

    /** @var Request $request */
    private $request;

    /** @var ConfigurationProcessorInterface $configurationProcessor */
    private $configurationProcessor;

    /** @var ResourceCollectionInterface $configuration */
    private $configuration;

    /** @var string $context */
    private $context;

    /**
     * AbstractConfigurationManager constructor.
     *
     * @param ConfigurationProcessorInterface $configurationProcessor
     * @param RequestStack                    $requestStack
     * @param FirewallMap                     $firewallMap
     */
    public function __construct(
        ConfigurationProcessorInterface $configurationProcessor,
        RequestStack $requestStack,
        FirewallMap $firewallMap
    ) {
        $this->configurationProcessor = $configurationProcessor;
        $this->request = $requestStack->getCurrentRequest();
        $this->firewallMap = $firewallMap;
    }

    /**
     * getContext
     *
     * @return string
     * @throws ConfigurationException
     */
    final public function getContext(): string
    {
        if (!$this->context) {
            $firewallConfig = $this->firewallMap->getFirewallConfig($this->request);

            if (!$firewallConfig) {
                throw new ConfigurationException('Firewall context is not configured', 1001);
            }

            $this->context = $firewallConfig->getName();
        }

        return $this->context;
    }

    /**
     * getConfiguration
     *
     * @return ResourceCollectionInterface
     * @throws ConfigurationException
     * @throws InvalidArgumentException
     */
    final public function getConfiguration(): ResourceCollectionInterface
    {
        if (!$this->configuration) {
            $this->configuration = $this->configurationProcessor->getCollection()[$this->getContext()] ?? null;

            if (!$this->configuration) {
                throw new ConfigurationException(sprintf(
                    'Resource context "%s" is not configured', $this->getContext()),
                    1002
                );
            }
        }

        return $this->configuration;
    }
}
