<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Action;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class CreateAction
 */
class CreateAction extends AbstractAction
{
    /**
     * __invoke
     *
     * @return Response
     */
    public function __invoke(): Response
    {
        return $this->render('@RhapsodyAdminLte/resource/action/create.html.twig');
    }
}
