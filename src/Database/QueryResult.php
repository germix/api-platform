<?php

namespace Germix\Api\Database;

/**
 * @author Germán Martínez
 */
class QueryResult
{
    /**
     * @var \mysqli_result
     */
    private $res;

    /**
     * Constructor
     *
     * @param \mysqli_result $res Resultado de una consulta
     */
    public function __construct($res)
    {
        $this->res = $res;
    }

    /**
     * Obtener la cantidad de filas
     *
     * @return number Cantidad de filas
     */
    public function rows()
    {
        return mysqli_num_rows($this->res);
    }
    /**
     * Obtener una fila del resultado de una consulta como un array asociativo
     *
     * @return array Arreglo asociativo referente a la fila actual del resultado de una consulta
     */
    public function data()
    {
        return mysqli_fetch_array($this->res, MYSQLI_ASSOC);
    }

    /**
     * Comprobar si hay resultados
     * 
     * @return bool true|false
     */
    public function empty()
    {
        return ($this->rows() == 0);
    }
}