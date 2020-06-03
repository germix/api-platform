<?php

namespace Germix\Api\Database;

use Germix\Api\Database\Exceptions\DatabaseConnectionException;
use Germix\Api\Database\Exceptions\DatabaseQueryException;

/**
 * @author Germán Martínez
 */
class Connection
{
    /**
     * @var \mysqli
     */
    private static $con = null;

    /**
     * Connection constructor.
     */
    public function __construct()
    {
        if(self::$con == null)
        {
            self::$con = new \mysqli(getenv('DB_HOST'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'), getenv('DB_NAME'));
            
            if(self::$con->connect_errno) 
            {
                throw new DatabaseConnectionException();
            }
            self::$con->query("SET NAMES 'utf8';");
        }
    }

    /**
     * Make a query
     *
     * @param string $query Query
     *
     * @return bool|QueryResult
     *
     * @throws DatabaseQueryException
     */
    public function query($query)
    {
        $res = self::$con->query($query);

        if(self::$con->errno != 0)
        {
            throw new DatabaseQueryException(self::$con->error, $query);
        }
        if($res instanceof \mysqli_result)
        {
            return new QueryResult($res);
        }
        return $res;
    }

    public function beginTransaction()
    {
        self::$con->begin_transaction();
    }

    public function commit()
    {
        self::$con->commit();
    }

    public function rollback()
    {
        self::$con->rollback();
    }
}
