<?php

namespace Rest\Repository;

use Rest\models\Address;
use Rest\models\AddressMapper;

class AddressRepository
{
    /**
     * @var AddressMapper
     */
    private $mapper;

    /**
     * AddressRepository constructor.
     * @param AddressMapper $mapper
     */
    public function __construct(AddressMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @param $id
     * @return Address
     */
    public function find($id)
    {
        $results = $this->mapper->select(['id' => (int) $id]);

        return $results[0];
    }

    /**
     * @return array
     */
    public function findAll()
    {
        return $this->mapper->select();
    }

    //TODO: check this
    public function save(Address $address)
    {
        $id = $address->getId();
        if (!$id) {
            $this->mapper->insert($address);
        } else {
            $this->mapper->update($id, $address);
        }
    }
}
