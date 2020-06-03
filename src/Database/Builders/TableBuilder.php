<?php

namespace Germix\Api\Database\Builders;

/**
 * @author Germán Martínez
 */
class TableBuilder
{
    /**
     * @var Connection
     */
    private $con;
    private $sql;
    private $needComma;

    public function __construct($con, $name)
    {
        $this->con = $con;
        $this->sql = "CREATE TABLE IF NOT EXISTS `$name`(";
    }

    public function finish()
    {
        $this->sql .= ") ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE = utf8_bin;";

        $res = $this->con->query($this->sql);
    }

    /**
     * @param string    $name       Nombre
     * @param boolean   $nn         No nulo
     * @param string    $type       Tipo
     *
     * @return SqlTableBuilder
     */
    public function field($name, $nn, $type)
    {
        $this->comma();

        $this->sql .= "`$name` $type ";
        if($nn)
            $this->sql .= " NOT NULL ";
        return $this;
    }

    /**
     * @param $name
     * @param bool $nn
     *
     * @return SqlTableBuilder
     */
    public function fieldText($name, $nn = false)
    {
        return $this->field($name, $nn, "TEXT");
    }

    /**
     * @param $name
     * @param bool $nn
     * @param integer $count
     * @return SqlTableBuilder
     */
    public function fieldTime($name, $nn = false, $count = 0)
    {
        if($count > 0)
            return $this->field($name, $nn, "TIME($count)");

        return $this->field($name, $nn, "TIME");
    }

    /**
     * @param $name
     * @param bool $nn
     * @return SqlTableBuilder
     */
    public function fieldDate($name, $nn = false)
    {
        return $this->field($name, $nn, "DATE");
    }

    /**
     * @param $name
     * @param bool $nn
     * @param integer $count
     * @return SqlTableBuilder
     */
    public function fieldDatetime($name, $nn = false, $count = 0)
    {
        if($count > 0)
            return $this->field($name, $nn, "DATETIME($count)");

        return $this->field($name, $nn, "DATETIME");
    }

    /**
     * @param $name
     * @param bool $nn
     * @return SqlTableBuilder
     */
    public function fieldInteger($name, $nn = false)
    {
        return $this->field($name, $nn, "INT");
    }

    /**
     * @param $name
     * @param integer $count
     * @param bool $nn
     * @return SqlTableBuilder
     */
    public function fieldVarchar($name, $count, $nn = false)
    {
        return $this->field($name, $nn, "VARCHAR($count)");
    }

    /**
     * @param $name
     * @param integer $count
     * @param bool $nn
     * @return SqlTableBuilder
     */
    public function fieldBoolean($name, $nn = false)
    {
        return $this->field($name, $nn, "BOOLEAN");
    }

    /**
     * @param $fields
     * @return SqlTableBuilder
     */
    public function primaryKey($fields)
    {
        $this->comma();

        $this->sql .= "PRIMARY KEY ($fields)";

        return $this;
    }

    /**
     * @param $columns
     * @param $foreignTable
     * @param $foreignColumns
     * @return SqlTableBuilder
     */
    function foreignKey($columns, $foreignTable, $foreignColumns)
    {
        $this->comma();

        $this->sql .= "FOREIGN KEY (`$columns`) REFERENCES `$foreignTable`(`$foreignColumns`)";

        return $this;
    }

    private function comma()
    {
        if($this->needComma)
            $this->sql .= " ,";
        $this->needComma = true;
    }
}