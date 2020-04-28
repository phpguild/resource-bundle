<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Tests\Configuration\TestCase;

use PhpGuild\ResourceBundle\Model\Action\ListAction;
use PhpGuild\ResourceBundle\Model\Action\Repository;
use PhpGuild\ResourceBundle\Model\Resource\ResourceCollection;
use PhpGuild\ResourceBundle\Model\Resource\ResourceCollectionInterface;
use PhpGuild\ResourceBundle\Model\Resource\ResourceElement;
use PhpGuild\ResourceBundle\Tests\Entity\User;

/**
 * Trait Test001Trait
 */
trait Test001Trait
{
    /**
     * testConfiguration001
     */
    public function testConfiguration001(): void
    {
        /** @var ResourceCollectionInterface $actualResourceCollection */
        foreach ($this->configurations['test001'] as $context => $actualResourceCollection) {
            $expectedResourceCollection = new ResourceCollection();

            switch ($context) {
                case 'main':
                    $expectedResourceCollection->setResources([
                        (new ResourceElement())
                            ->setModel(User::class)
                            ->setLabel('main.user.label')
                            ->setPrimaryRoute($this->getRoute('list'))
                            ->setActions([
                                (new ListAction())
                                    ->setDefault(false)
                                    ->setRepository((new Repository())->setModel(User::class))
                                    ->setPrimaryRoute($this->getRoute('list'))
                                    ->setFields($this->getListFields([ 'id', 'firstName', 'lastName', 'username', 'email', 'roles', '_actions' ])),
                            ])
                    ]);
                    break;
            }

            self::assertObject($expectedResourceCollection, $actualResourceCollection);
            self::assertObject($actualResourceCollection, $expectedResourceCollection);
        }
    }
}
