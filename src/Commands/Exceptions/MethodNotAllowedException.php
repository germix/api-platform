<?php

namespace Germix\Api\Commands\Exceptions;

use Germix\Api\Base\AbstractException;

/**
 * @author Germán Martínez
 */
class MethodNotAllowedException extends AbstractException
{
    public function __construct(\Throwable $previous = null)
    {
        parent::__construct(405, 'Method not allowed', $previous);
    }
}
