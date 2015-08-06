<?php

namespace Rest\models;

use \Rest\ApiException;

class Address
{
	public $id;
	public $label;
	public $street;
	public $houseNumber;
	public $postalCode;
	public $city;
	public $country;

    private $_tableFieldsMap = array(
        'ADDRESSID' => 'id',
        'LABEL' => 'label',
        'STREET' => 'street',
        'HOUSENUMBER' => 'houseNumber',
        'POSTALCODE' => 'postalCode',
        'CITY' => 'city',
        'COUNTRY' => 'country'
    );

    private $_requiredFields = array('label', 'street', 'houseNumber', 'postalCode', 'city', 'country');

	public function __construct($data = null)
	{        
        if (is_array($data)) {
            if (isset($data['id'])) {
            	$this->id = $data['id'];
            } 

            $this->label = !empty($data['label']) ? $data['label'] : null;
            $this->street = !empty($data['street']) ? $data['street'] : null;
            $this->houseNumber = !empty($data['houseNumber']) ? $data['houseNumber'] : null;
            $this->postalCode = !empty($data['postalCode']) ? $data['postalCode'] : null;
            $this->city = !empty($data['city']) ? $data['city'] : null;
            $this->country = !empty($data['country']) ? $data['country'] : null;
        }
	}

    public function __set($name, $value)
    {
        if (!array_key_exists($name, $this->_tableFieldsMap)) {
            $this->{$name} = $value;
        } else {
            $this->{$this->_tableFieldsMap[$name]} = $value;
        }
    }

    public function setAttributes($data)
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function validate()
    {
        $undefinedAttributes = array();
        foreach ($this->_requiredFields as $fieldName) {
            if ($this->{$fieldName} === null) {
                $undefinedAttributes[] = $fieldName;
            }
        }

        if (count($undefinedAttributes)) {
            throw new ApiException(400, implode(', ', $undefinedAttributes) . " wasn't set!");
        } else {
            return true;
        }
    }
}