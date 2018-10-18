<?php

namespace Rest\models;

class Address
{
	public $id;
	public $label;
	public $street;
	public $house_number;
	public $postal_code;
	public $city;
	public $country;

    private $validateError;

    private $_tableFieldsMap = array(
        'id' => 'id',
        'label' => 'label',
        'street' => 'street',
        'house_number' => 'house_number',
        'postal_code' => 'postal_code',
        'city' => 'city',
        'country' => 'country'
    );

    private $_requiredFields = array('label', 'street', 'house_number', 'postal_code', 'city', 'country');

	public function __construct($data = null)
	{        
        if (is_array($data)) {
            if (isset($data['id'])) {
            	$this->id = $data['id'];
            } 

            $this->label = !empty($data['label']) ? $data['label'] : null;
            $this->street = !empty($data['street']) ? $data['street'] : null;
            $this->houseNumber = !empty($data['house_number']) ? $data['house_number'] : null;
            $this->postalCode = !empty($data['postal_code']) ? $data['postal_code'] : null;
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
            $this->validateError = implode(', ', $undefinedAttributes) . " wasn't set!";
            return false;
        } else {
            return true;
        }
    }

    public function getValidateError()
    {
        return $this->validateError;
    }
}