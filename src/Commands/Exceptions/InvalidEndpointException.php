<?php

namespace Germix\Api\Commands\Exceptions;

use Germix\Api\Base\AbstractException;

/**
 * @author Germán Martínez
 */
class InvalidEndpointException extends AbstractException
{
    public function __construct(\Throwable $previous = null)
    {
        parent::__construct(404, 'Invalid endpoint', $previous);
    }
}
