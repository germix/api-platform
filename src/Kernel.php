<?php

namespace Germix\Api;

use Germix\Api\Base\AbstractException;
use Germix\Api\Commands\CommandCaller;

/**
 * @author Germán Martínez
 */
class Kernel
{
    /**
     * Run kernel
     */
    public function run($params)
    {
        error_reporting(0);
        register_shutdown_function(function()
        {
            if(!is_null($e = error_get_last()))
            {
                echo json_encode($e);
                http_response_code(500);
                header('Content-Type: application/json');
            }
        });
        try
        {
            $this->internalRun($params);
        }
        catch(AbstractException $e)
        {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            http_response_code($e->getCode());
            header('Content-Type: application/json');
        }
    }

    private function internalRun($params)
    {
        //
        // Obtener el método de la solicitud
        //
        $method = strtoupper($_SERVER['REQUEST_METHOD']);
        if($method == 'OPTIONS') die();

        //
        // Obtener los elementos que componen a la URL de la solicitud
        //
        if(isset($_SERVER['PATH_INFO']))
        {
            $s = $_SERVER['PATH_INFO'];
        }
        else if(isset($_SERVER['REDIRECT_URL']))
        {
            $s = $_SERVER['REDIRECT_URL'];
            if(!isset($s))
                $s = ltrim($s, '/');
        }
        if(!isset($s))
            $urlParts = [];
        else
            $urlParts = explode('/', trim($s, '/'));

        (new CommandCaller())->call($params['commands_file'], $method, $urlParts);
    }
}
