<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Action;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class DeleteAction
 */
class DeleteAction extends AbstractAction
{
    /**
     * __invoke
     *
     * @return Response
     */
    public function __invoke(): Response
    {
        return new Response('DeleteAction');
    }
}
