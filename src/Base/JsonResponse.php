<?php

namespace Germix\Api\Base;

/**
 * @author Germán Martínez
 */
class JsonResponse implements Response
{
    /**
     * @var array
     */
    private $data;

    private $http_code;

    public function __construct($data, $http_code = 200)
    {
        $this->data = $data;
        $this->http_code = $http_code;
    }
    public function dump()
    {
        header('Content-Type: application/json');
        http_response_code($this->http_code);

        echo json_encode($this->data);
    }
}