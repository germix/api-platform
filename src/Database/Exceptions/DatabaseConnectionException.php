<?php

namespace Germix\Api\Database\Exceptions;

use Germix\Api\Base\AbstractException;

/**
 * @author Germán Martínez
 */
class DatabaseConnectionException extends AbstractException
{
    public function __construct(\Throwable $previous = null)
    {
        parent::__construct(500, "Can't connect to the database", $previous);
    }
}
