<?php

namespace Germix\Api\Database\Exceptions;

use Germix\Api\Base\AbstractException;

/**
 * @author Germán Martínez
 */
class DatabaseQueryException extends AbstractException
{
    private $error;

    private $query;

    public function __construct($error, $query, \Throwable $previous = null)
    {
        parent::__construct(500, "Sql query error", $previous);
        $this->error = $error;
        $this->query = $query;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getQuery()
    {
        return $this->query;
    }
}
