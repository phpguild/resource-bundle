# Resource Bundle

## Installation

Install with composer

    composer req phpguild/resource-bundle

## Configuration

Create `src/DependencyInjection/Configuration.php` file into your bundle

    <?php
    
    declare(strict_types=1);
    
    namespace Acme\FooBundle\DependencyInjection;
    
    use PhpGuild\ResourceBundle\DependencyInjection\ResourceConfigurationTrait;
    use Symfony\Component\Config\Definition\Builder\TreeBuilder;
    use Symfony\Component\Config\Definition\ConfigurationInterface;
    
    /**
     * Class Configuration
     */
    class Configuration implements ConfigurationInterface
    {
        use ResourceConfigurationTrait;
    
        /**
         * getConfigTreeBuilder
         *
         * @return TreeBuilder
         */
        public function getConfigTreeBuilder(): TreeBuilder
        {
            $treeBuilder = new TreeBuilder('acme_foo');
            $rootNode = $treeBuilder->getRootNode();
    
            $this->addResourceConfiguration($rootNode);
    
            return $treeBuilder;
        }
    }

Create ConfigurationProcessor

    <?php
    
    declare(strict_types=1);
    
    namespace Acme\FooBundle\Configuration;
    
    use PhpGuild\ResourceBundle\Configuration\AbstractConfigurationProcessor;
    
    /**
     * Class AcmeFooConfigurationProcessor
     */
    final class AcmeFooConfigurationProcessor extends AbstractConfigurationProcessor
    {
    }

Create ConfigurationManager

    <?php
    
    declare(strict_types=1);
    
    namespace Acme\FooBundle\Configuration;
    
    use PhpGuild\ResourceBundle\Configuration\AbstractConfigurationManager;
    
    /**
     * Class AcmeFooConfigurationManager
     */
    final class AcmeFooConfigurationManager extends AbstractConfigurationManager
    {
    }

Configure service `config/services.yaml`

    services:
      Acme\FooBundle\Configuration\AcmeFooConfigurationProcessor:
        arguments: [ '%acme_foo%' ]

      Acme\FooBundle\Configuration\AcmeFooConfigurationManager:
        arguments: [ '@Acme\FooBundle\Configuration\AcmeFooConfigurationProcessor' ]

Configure routing `config/routes.yaml`

    phpguild_resource:
      resource: '@PhpGuildResourceBundle/Resources/config/routing.yaml'

Configure resources `config/packages/acme_foo.yaml`

    acme_foo:
      contexts:
        main: ~
