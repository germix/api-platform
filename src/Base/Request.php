<?php

namespace Germix\Api\Base;

use Germix\Api\Exceptions\BadRequestException;

/**
 * @author Germán Martínez
 */
class Request
{
    const METHOD_HEAD = 'HEAD';
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_PATCH = 'PATCH';
    const METHOD_DELETE = 'DELETE';
    const METHOD_PURGE = 'PURGE';
    const METHOD_OPTIONS = 'OPTIONS';
    const METHOD_TRACE = 'TRACE';
    const METHOD_CONNECT = 'CONNECT';

    /**
     * @var string
     */
    public $method;

    /**
     * @var ParameterBag
     */
    public $query;

    /**
     * @var ParameterBag
     */
    public $request;

    /**
     * Request constructor.
     *
     * @param string $method     The method
     */
    public function __construct($method)
    {
        $this->method = $method;
        $this->query = new ParameterBag($_GET);
        if($method == self::METHOD_POST)
        {
            $this->request = new ParameterBag($_POST);
        }
        else if(
            $method == self::METHOD_PUT
            || $method == self::METHOD_PATCH
            || $method == self::METHOD_DELETE)
        {
            $parameters = array();
            parse_str(file_get_contents('php://input'), $parameters);

            $this->request = new ParameterBag($parameters);
        }
        else
        {
            $this->request = new ParameterBag(array());
        }
    }

    /**
     * Get body as json
     * 
     * @return array
     */
    public function json()
    {
        $json = json_decode(file_get_contents('php://input'), true);
        if(json_last_error())
        {
            throw new BadRequestException();
        }
        return $json;
    }
}


