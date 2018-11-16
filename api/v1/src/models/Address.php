<?php

namespace Rest\models;

use PHPUnit\Runner\Exception;
use Rest\Utils\Request;

class Address
{
    /** @var integer */
    public $id;

    /** @var string */
    public $label;

    /** @var string */
    public $street;

    /** @var string */
    public $houseNumber;

    /** @var string */
    public $postalCode;

    /** @var string */
    public $city;

    /** @var string */
    public $country;

    /** @var string */
    public $comments;

    private $validationError = '';

    private $updated = [];

    private $_tableFieldsMap = array(
        'id' => 'id',
        'label' => 'label',
        'street' => 'street',
        'house_number' => 'house_number',
        'postal_code' => 'postal_code',
        'city' => 'city',
        'country' => 'country',
        'comments' => 'comments',
    );

    private $requiredFields = array('label', 'street', 'house_number', 'postal_code', 'city', 'country');

    /**
     * Address constructor.
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        foreach ($attributes as $key => $value) {
            $method = 'set' . ucfirst(strtolower($key));
            if (method_exists($this, $method))
                $this->{$method}($value);
        }
    }

    public function __set($name, $value)
    {
        $method = 'set' . ucfirst($this->nameCamelCase($name));
        if (method_exists($this, $method)) {
            $this->{$method}($value);
        } elseif (property_exists($this, $name)) {
            $this->{$name} = $value;
        } else {
            throw new Exception("Property '$name' does not exist");
        }
    }

    public function __get($name)
    {
        $name = $this->nameCamelCase($name);
        if (property_exists($this, $name)) {
            return $this->{$name};
        }
    }

    // TODO: move to anotherPlace
    public function nameCamelCase($name)
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

    public function validate()
    {
        foreach ($this->requiredFields as $key) {
            if (!$this->{$key}) {
                $this->validationError = "Required property '$key' can not be empty";
            }
        }
    }

    /**
     * return bool
     */
    public function isValid()
    {
        $this->validate();
        return !(bool) $this->getValidationError();
    }

    /**
     * @return string
     */
    public function getValidationError()
    {
        return $this->validationError;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * @return string
     */
    public function getHouseNumber()
    {
        return $this->houseNumber;
    }

    /**
     * @param string $houseNumber
     */
    public function setHouseNumber($houseNumber)
    {
        $this->houseNumber = $houseNumber;
    }

    /**
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param string $postalCode
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    public function populateWith($data)
    {
        foreach ($data as $key => $value) {
            $this->updated[] = $key;
            $this->__set($key, $value);
        }
    }

    /**
     * @return array
     */
    public function getUpdatedKeys()
    {
        return $this->updated;
    }
}