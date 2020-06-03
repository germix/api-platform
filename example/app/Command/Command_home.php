<?php

namespace App\Command;

use Germix\Api\Base\JsonResponse;
use Germix\Api\Base\Request;
use Germix\Api\Commands\Command;

class Command_home implements Command
{
    public function run(Request $request, array $urlParameters)
    {
        return new JsonResponse([
            'name' => 'Test API',
            'version' => '1.0'
        ]);
    }
}
