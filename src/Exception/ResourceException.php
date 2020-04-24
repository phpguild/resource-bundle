<?php

declare(strict_types=1);

namespace PhpGuild\ResourceBundle\Exception;

/**
 * Class ResourceException
 */
class ResourceException extends \Exception
{
    /** @var string */
    protected const SUPPORT_URL = 'https://github.com/phpguild/resource-bundle/wiki/Exceptions';

    /**
     * ResourceException constructor.
     *
     * @param string          $message
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', $code = 0, \Throwable $previous = null)
    {
        parent::__construct(
            sprintf($message . ' ( ' . static::SUPPORT_URL . '#err%1$s )', $code),
            $code,
            $previous
        );
    }
}
