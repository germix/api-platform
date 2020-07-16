<?php

namespace Germix\Api\Database\Builders;

/**
 * @author Germán Martínez
 */
class InsertBuilder
{
    /**
     * @var Connection
     */
    private $con;
    private $tableName;

    private $fields = [];

    public function __construct($con, $tableName)
    {
        $this->con = $con;
        $this->tableName = $tableName;
    }

    public function add($field, $value)
    {
        $this->fields[$field] = $value;
        return $this;
    }

    public function flush()
    {
        $this->con->query($this->getSqlQuery());
    }

    public function getSqlQuery()
    {
        $sql = "INSERT INTO `$this->tableName` (";

        $first = true;
        foreach($this->fields as $key => $value)
        {
            if(!$first)
                $sql .= ',';
            $first = false;
            $sql .= "`$key`";
        }
        $first = true;
        $sql .= ') VALUES(';
        foreach($this->fields as $key => $value)
        {
            if(!$first)
                $sql .= ',';
            $first = false;
            $value = str_replace("'", "\'", $value);
            $sql .= "'$value'";
        }
        $sql .= ');';

        return $sql;
    }
}
