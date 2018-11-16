<?php

namespace Rest\Utils;

use PHPUnit\Runner\Exception;

class Request
{
    protected $method;
    protected $body;
    protected $uri;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];

        if ($this->method == 'POST' || $this->method == 'PUT') {
            $content = file_get_contents('php://input');
            $this->body = json_decode($content);

            //TODO: return response in case of bad json
            if (!$this->body) {
                throw new Exception("Wrong request body format (JSON string is not valid)");
            }
        }
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }
}
