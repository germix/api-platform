<?php

namespace Germix\Api\Exceptions;

use Germix\Api\Base\AbstractException;

/**
 * @author Germán Martínez
 */
class BadRequestException extends AbstractException
{
    public function __construct(\Throwable $previous = null)
    {
        parent::__construct(400, "Bad request", $previous);
    }
}
