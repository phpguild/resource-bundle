<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Action;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ListAction
 */
class ListAction extends AbstractAction
{
    /**
     * __invoke
     *
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        return $this->render('@RhapsodyAdminLte/resource/action/list.html.twig', [
            'resourceParameters' => $this->getResourceParameters(),
            'resourceCollection' => $this->getResourceData(),
        ]);
    }
}
