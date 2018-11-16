<?php

namespace Rest\Helpers;

class CamelCaseHelper
{
    public function __invoke($name)
    {
        $parts = explode('_', $name);

        $i = 0;
        $camelCased = array_reduce($parts, function ($carry, $item) use (&$i) {
            $item = $i > 0 ? ucfirst($item) : $item;
            $i++;
            return $carry . $item;
        }, '');

        return $camelCased;
    }
}
