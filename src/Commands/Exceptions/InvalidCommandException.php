<?php

namespace Germix\Api\Commands\Exceptions;

use Germix\Api\Base\AbstractException;

/**
 * @author Germán Martínez
 */
class InvalidCommandException extends AbstractException
{
    public function __construct(\Throwable $previous = null)
    {
        parent::__construct(500, "Invalid command", $previous);
    }
}
