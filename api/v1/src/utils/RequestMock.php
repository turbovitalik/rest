<?php

namespace Rest\Utils;

class RequestMock extends Request
{
    public function getBody()
    {
        $json = <<<JSON
        {
            "label":"New Address 2",
            "country": "Russia"
        }
JSON;

        return json_decode($json);
    }
}
