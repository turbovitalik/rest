<?php

namespace Rest\Utils;

use Rest\models\Address;

class AddressManager
{
    public function create($attributes)
    {
        foreach ($attributes as $key => $value) {
            if (!property_exists($this, $key)) {
                $this->validationError = "Property '$key' does not exist in address entity";
//                throw new \Exception("Property " . $key . " is not defined in Address object");
            }
            if (!$value && in_array($key, $this->requiredFields)) {
                $this->validationError = "Required property '$key' can not be empty";
            }

            $this->{$key} = $value;
        }
    }

    /**
     * @param $data
     * @return Address
     * @throws \Exception
     */
    public function add($data)
    {
        $address = new Address($data);

        return $address;
    }
}
