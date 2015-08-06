<?php

namespace Rest;

class ApiException extends \Exception
{
    public function __construct($code, $message = null)
    {
        parent::__construct($message, $code);
    }
}