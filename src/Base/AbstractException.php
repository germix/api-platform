<?php

namespace Germix\Api\Base;

/**
 * @author Germán Martínez
 */
abstract class AbstractException extends \Exception
{
    public function __construct(int $statusCode, string $message, \Throwable $previous = null)
    {
        parent::__construct($message, $statusCode, $previous);
    }
};
