<?php

namespace Rest\views;

use Rest\Utils\JsonResponse;

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

    public function renderResourceNotFound()
    {
        return $this->renderJson(404, 'Resource not found');
    }

    public function renderJson($code, $content)
    {
        $content = json_encode($content);

        $response = new JsonResponse();
        $response->setContent($content);
        $response->setStatus($code);

        return $response;
    }

    public function setStatus($code)
    {
        $protocol = 'HTTP/1.1';
        $code .= ' ' . $this->codes[strval($code)];
        header("$protocol $code");
    }

}