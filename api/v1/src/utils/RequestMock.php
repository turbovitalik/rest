<?php
/**
 * Created by PhpStorm.
 * User: turbovitalik
 * Date: 11/10/18
 * Time: 12:44 PM
 */

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