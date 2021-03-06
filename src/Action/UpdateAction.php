<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Action;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class UpdateAction
 */
class UpdateAction extends AbstractAction
{
    /**
     * __invoke
     *
     * @return Response
     */
    public function __invoke(): Response
    {
        return $this->render('@RhapsodyAdminLte/resource/action/update.html.twig');
    }
}
