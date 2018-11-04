<?php

namespace Rest\Utils;

use Rest\models\Address;

class AddressManager
{
    /**
     * @param $data array
     */
    public function createFromArray($data)
    {
        $address = new Address();

        foreach ($data as $key => $value) {
            $setMethod = 'set' . ucfirst($key);
            $address->{$setMethod}($value);
        }

        return $address;
    }
}