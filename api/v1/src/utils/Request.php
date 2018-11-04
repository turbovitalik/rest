<?php

namespace Rest\Utils;

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
            $this->body = file_get_contents('php://input');
            //todo: check the body
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