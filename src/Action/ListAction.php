<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Action;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ListAction
 */
class ListAction extends AbstractController
{
    /**
     * __invoke
     *
     * @return Response
     */
    public function __invoke(): Response
    {
        return new Response('ListAction');
    }
}
