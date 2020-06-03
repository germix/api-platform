<?php

namespace Germix\Api\Commands\Exceptions;

use Germix\Api\Base\AbstractException;

/**
 * @author Germán Martínez
 */
class InvalidCommandResponseException extends AbstractException
{
    public function __construct($command, \Throwable $previous = null)
    {
        parent::__construct(500, 'The response from the command "' . get_class($command) . '" is null or invalid', $previous);
    }
}
