<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Model\Action;

use PhpGuild\ResourceBundle\Model\Field\FieldInterface;
use PhpGuild\ResourceBundle\Model\RouteInterface;

/**
 * Interface ActionInterface
 */
interface ActionInterface
{
    public const ROUTE_NAME = '{_context}_{_resource}_{_action}';
    public const ROUTE_PATH = '{_resource}/{_action}';
    public const ROUTE_METHODS = [ 'GET' ];
    public const ROUTE_PARAMETERS = [];
    public const ROUTE_REQUIREMENTS = [];

    /**
     * getRoute
     *
     * @return RouteInterface|null
     */
    public function getRoute(): ?RouteInterface;

    /**
     * setRoute
     *
     * @param RouteInterface|null $route
     *
     * @return ActionInterface|self
     */
    public function setRoute(RouteInterface $route): ActionInterface;

    /**
     * isDefault
     *
     * @return bool
     */
    public function isDefault(): bool;

    /**
     * setDefault
     *
     * @param bool $default
     *
     * @return ActionInterface|self
     */
    public function setDefault(bool $default): ActionInterface;

    /**
     * getController
     *
     * @return string|null
     */
    public function getController(): ?string;

    /**
     * setController
     *
     * @param string|null $controller
     *
     * @return ActionInterface|self
     */
    public function setController(?string $controller): ActionInterface;

    /**
     * getRepository
     *
     * @return Repository|null
     */
    public function getRepository(): ?Repository;

    /**
     * setRepository
     *
     * @param Repository|null $repository
     *
     * @return ActionInterface|self
     */
    public function setRepository(?Repository $repository): ActionInterface;


    /**
     * getFields
     *
     * @return FieldInterface[]
     */
    public function getFields(): array;

    /**
     * setFields
     *
     * @param FieldInterface[] $fields
     *
     * @return ActionInterface|self
     */
    public function setFields(array $fields): ActionInterface;
}
