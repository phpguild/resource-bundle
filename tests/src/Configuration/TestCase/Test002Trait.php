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
 * Trait Test002Trait
 */
trait Test002Trait
{
    /**
     * testConfiguration002
     */
    public function testConfiguration002(): void
    {
        /** @var ResourceCollectionInterface $actualResourceCollection */
        foreach ($this->configurations['test002'] as $context => $actualResourceCollection) {
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
                                    ->setDefault(true)
                                    ->setRepository((new Repository())->setModel(User::class))
                                    ->setRoute($this->getRoute('list'))
                                    ->setFields($this->getListFields([ 'firstName', 'lastName', 'username', 'email', 'createdAt' ])),
                            ])
                    ]);
                    break;
            }

            self::assertObject($expectedResourceCollection, $actualResourceCollection);
            self::assertObject($actualResourceCollection, $expectedResourceCollection);
        }
    }
}
