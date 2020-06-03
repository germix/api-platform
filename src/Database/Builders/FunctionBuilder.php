<?php

namespace Germix\Api\Database\Builders;

/**
 * @author Germán Martínez
 */
class FunctionBuilder
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

        $this->sql .= PHP_EOL . "CREATE OR REPLACE FUNCTION `$name`(";
    }

    /**
     * Agregar parámetro
     *
     * @param string    $name   Nombre del parámetro
     * @param string    $type   Tipo de datos del parámetro
     * @return $this
     */
    public function addParameter($name, $type)
    {
        $this->comma();
        $this->sql .= $name . ' ' . $type;
        return $this;
    }

    /**
     * Asignar tipo de retorno
     *
     * @param $type Tipo de dato de retorno
     *
     * @return $this
     */
    public function setReturn($type)
    {
        $this->sql .= ') RETURNS ' . $type;
        $this->sql .= PHP_EOL . 'READS SQL DATA';
        $this->sql .= PHP_EOL . 'BEGIN';
        return $this;
    }

    /**
     * Asignar cuerpo
     *
     * @param $body Código de la función
     *
     * @return $this
     */
    public function setBody($body)
    {
        $this->sql .= PHP_EOL . $body;
        return $this;
    }

    /**
     * Finalizar
     */
    public function finish()
    {
        $this->sql .= PHP_EOL . 'END';

        $ret = $this->con->query($this->sql);
    }

    private function comma()
    {
        if($this->needComma)
            $this->sql .= " ,";
        $this->needComma = true;
    }
}