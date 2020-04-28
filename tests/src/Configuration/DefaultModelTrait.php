<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Tests\Configuration;

use Doctrine\Common\Inflector\Inflector;
use PhpGuild\ResourceBundle\Model\Action\CreateAction;
use PhpGuild\ResourceBundle\Model\Action\DeleteAction;
use PhpGuild\ResourceBundle\Model\Action\ListAction;
use PhpGuild\ResourceBundle\Model\Action\UpdateAction;
use PhpGuild\ResourceBundle\Model\Field\FieldInterface;
use PhpGuild\ResourceBundle\Model\Field\ListField;
use PhpGuild\ResourceBundle\Model\Route;
use PhpGuild\ResourceBundle\Model\RouteInterface;
use PhpGuild\ResourceBundle\Model\Type\DefaultType;
use PhpGuild\ResourceBundle\Model\Type\Link\LinkCollectionType;
use PhpGuild\ResourceBundle\Model\Type\Link\LinkType;

/**
 * Class ConfigurationProcessorTest
 */
trait DefaultModelTrait
{
    /** @var string $defaultContext */
    private static $defaultContext = 'main';

    /** @var string $defaultResource */
    private static $defaultResource = 'user';

    /**
     * getRoute
     *
     * @param string      $action
     * @param string|null $context
     * @param string|null $resource
     *
     * @return RouteInterface
     */
    private function getRoute(string $action, string $context = null, string $resource = null): RouteInterface
    {
        $context = $context ?? static::$defaultContext;
        $resource = $resource ?? static::$defaultResource;

        switch ($action) {
            default:
            case 'list':
                $actionClass = ListAction::class;
                break;
            case 'create':
                $actionClass = CreateAction::class;
                break;
            case 'update':
                $actionClass = UpdateAction::class;
                break;
            case 'delete':
                $actionClass = DeleteAction::class;
                break;
        }

        return (new Route())
            ->setName(str_replace([ '{_context}', '{_resource}', '{_action}' ], [ $context, $resource, $action ], $actionClass::ROUTE_NAME))
            ->setPath(str_replace([ '{_context}', '{_resource}', '{_action}' ], [ $context, $resource, $action ], $actionClass::ROUTE_PATH))
            ->setParameters($actionClass::ROUTE_PARAMETERS)
            ->setMethods($actionClass::ROUTE_METHODS)
        ;
    }

    /**
     * getListFields
     *
     * @param array       $nameList
     * @param string|null $context
     * @param string|null $entity
     *
     * @return array
     */
    private function getListFields(array $nameList, string $context = null, string $entity = null): array
    {
        $collection = [];

        foreach ($nameList as $name) {
            $collection[] = $this->getListField($name, $context, $entity);
        }

        return $collection;
    }

    /**
     * getListField
     *
     * @param string      $name
     * @param string|null $context
     * @param string|null $resource
     *
     * @return FieldInterface
     */
    private function getListField(string $name, string $context = null, string $resource = null): FieldInterface
    {
        $context = $context ?? static::$defaultContext;
        $resource = $resource ?? static::$defaultResource;

        $label = sprintf('%s.%s.%s.list_label', $context, $resource, Inflector::tableize($name));

        $listField = (new ListField())
            ->setName($name)
            ->setLabel($label)
            ->setType((new DefaultType())->setName('string'));

        switch ($name) {
            case 'id':
                $listField->setType((new DefaultType())->setName('integer'));
                break;
            case 'createdAt':
            case 'updatedAt':
                $listField->setType((new DefaultType())->setFormat('d/m/Y'));
                break;
            case 'roles':
                $listField->setType((new DefaultType())->setName('array'));
                break;
            case '_actions':
                return $listField
                    ->setLabel(sprintf('%s.ui.actions', $context))
                    ->setType(
                        (new LinkCollectionType())
                            ->setName('links')
                            ->setCollection([
                                (new LinkType())
                                    ->setName('link')
                                    ->setLabel(sprintf('%s.ui.update', $context))
                                    ->setRoute($this->getRoute('update', $context, $resource)),
                            ])
                    );
                break;
        }

        return $listField;
    }
}
