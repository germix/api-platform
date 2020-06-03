<?php

namespace Germix\Api\Commands\Exceptions;

use Germix\Api\Base\AbstractException;

/**
 * @author Germán Martínez
 */
class UnexpectedTokenException extends AbstractException
{
    public function __construct($expected, $unexpected, \Throwable $previous = null)
    {
        parent::__construct(500, 'Expected ' . $expected . ', but found ' . $unexpected, $previous);
    }
}
