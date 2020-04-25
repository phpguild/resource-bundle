<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Action;

use Doctrine\Persistence\ObjectRepository;
use PhpGuild\ResourceBundle\Model\Resource\ResourceParametersInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AbstractAction
 */
abstract class AbstractAction extends AbstractController
{
    /** @var ResourceParametersInterface $resourceParameters */
    protected $resourceParameters;

    /**
     * getResourceParameters
     *
     * @return ResourceParametersInterface
     */
    public function getResourceParameters(): ResourceParametersInterface
    {
        if (!$this->resourceParameters) {
            /** @var Request $request */
            $request = $this->get('request_stack')->getCurrentRequest();

            /** @var ResourceParametersInterface $resourceParameters */
            $this->resourceParameters = unserialize($request->attributes->get('_resourceParameters'), [
                'allowed_classes' => true,
            ]);
        }

        return $this->resourceParameters;
    }

    /**
     * getEntityRepository
     *
     * @return ObjectRepository
     */
    public function getEntityRepository(): ObjectRepository
    {
        $model = $this->getResourceParameters()->getRepository()->getModel();

        return $this->get('doctrine')->getRepository($model);
    }

    /**
     * getResourceData
     *
     * @param mixed ...$parameters
     *
     * @return mixed
     */
    public function getResourceData(...$parameters)
    {
        $method = $this->getResourceParameters()->getRepository()->getMethod();

        return $this->getEntityRepository()->{$method}(...$parameters);
    }
}
