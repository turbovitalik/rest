<?php

namespace Rest\views;

class JsonView
{
    private $codes = array(
        '200' => 'OK',
        '201' => 'Created',
        '204' => 'No Content',
        '400' => 'Bad Request',
        '404' => 'Not Found',
        '500' => 'Internal Server Error'
    );

    public function render($code, $data)
    {
        $this->setStatus($code);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function setStatus($code)
    {
        $protocol = 'HTTP/1.1';
        $code .= ' ' . $this->codes[strval($code)];
        header("$protocol $code");
    }

}