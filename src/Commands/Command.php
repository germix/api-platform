<?php

namespace Germix\Api\Commands;

use Germix\Api\Base\Request;

/**
 * Esta interface representa un comando asociado a una URL
 *
 * @author Germán Martínez
 * 
 */
interface Command
{
    /**
     * Execute command
     *
     * @param Request   $request            The request
     * @param array     $urlParameters      The url parameters for this command
     *
     * @return Response
     */
    public function run(Request $request, array $urlParameters);
}

