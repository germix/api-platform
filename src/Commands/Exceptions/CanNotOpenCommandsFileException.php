<?php

namespace Germix\Api\Commands\Exceptions;

use Germix\Api\Base\AbstractException;

/**
 * @author Germán Martínez
 */
class CanNotOpenCommandsFileException extends AbstractException
{
    public function __construct($fileName, \Throwable $previous = null)
    {
        parent::__construct(500, "Can't open the commands description file \"$fileName\"", $previous);
    }
}
