<?php

namespace Germix\Api\Base;

/**
 * @author Germán Martínez
 */
class ParameterBag
{
    /**
     * @var array
     */
    private $parameters;

    /**
     * @param array $parameters An array of parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * Returns true if the parameter is defined
     *
     * @param string $key The key
     *
     * @return bool true if the parameter exists, false otherwise
     */
    public function has($key)
    {
        return \array_key_exists($key, $this->parameters);
    }

    /**
     * Returns a parameter by name
     *
     * @param string        $key        The key
     * @param mixed|null    $default    The default value if the parameter key does not exist
     *
     * @return mixed|null
     */
    public function get($key, $default = null)
    {
        return \array_key_exists($key, $this->parameters) ? $this->parameters[$key] : $default;
    }
};

