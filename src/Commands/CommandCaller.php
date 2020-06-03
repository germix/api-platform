<?php

namespace Germix\Api\Commands;

use Germix\Api\Base\Request;
use Germix\Api\Base\Response;
use Germix\Api\Commands\Exceptions\CanNotOpenCommandsFileException;
use Germix\Api\Commands\Exceptions\InvalidCommandException;
use Germix\Api\Commands\Exceptions\InvalidCommandResponseException;
use Germix\Api\Commands\Exceptions\InvalidEndpointException;
use Germix\Api\Commands\Exceptions\MethodNotAllowedException;
use Germix\Api\Commands\Exceptions\UnexpectedTokenException;

/**
 * Esta clase obtiene el comando y lo ejecuta
 * 
 * @author Germán Martínez
 * 
 */
class CommandCaller
{
    /**
     * Llamar al comando asociado a la url
     * 
     * @param string    $fileName           Nombre del archivo que contiene la configuración de comandos
     * @param string    $method             Método de la consulta
     * @param array     $urlParts           Arreglo de las partes que componen a la URL
     *
     * @return Command
     *
     * @throws MethodNotAllowedException
     * @throws InvalidEndpointException
     * @throws UnexpectedTokenException
     * @throws CanNotOpenCommandsFileException
     * @throws InvalidCommandException
     * @throws InvalidCommandResponseException
     *
     */
    public function call($fileName, $method, $urlParts)
    {
        //
        // Find command
        //
        $urlParameters = array();
        $command = (new CommandParser())->parse($fileName, $method, $urlParts, $urlParameters);

        //
        // Create the request
        //
        $request = new Request($method);

        //
        // Call command and get response
        //
        $response = $command->run($request, $urlParameters);

        if($response == null || !($response instanceof Response))
        {
            throw new InvalidCommandResponseException($command);
        }
        $response->dump();
    }
}