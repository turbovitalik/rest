<?php

namespace Rest\controllers;

use \Rest\models\AddressRepository;
use \Rest\models\Address;

class AddressController
{
    /**
     * Get addresses list or address info by id
     *
     * @url GET /addresses
     * @url GET /addresses/$id
     */
    public function view($id = null)
    {
        $addressRepository = new AddressRepository();

        if ($id) {
            $result = $addressRepository->find($id);
            if (!$result) {
                throw new \Rest\ApiException(404, "Address with ID $id doesn't exist");
            }
        } else {
            $result = $addressRepository->findAll();
        }

        return $result;
    }

    /**
     * Save new address to DB
     *
     * @url POST /addresses
     */
    public function create($data)
    {
        $address = new Address($data);
        if ($address->validate()) {
            $addressRepository = new AddressRepository();
            $result = $addressRepository->add($address);

            return $result;
        }
    }

    /**
     * Update address in DB
     *
     * @url PUT /addresses/$id
     */
    public function update($id, $data)
    {
        $addressRepository = new AddressRepository();
        $address = $addressRepository->find($id);

        if (!$address) {
            throw new \Rest\ApiException(400, "Adress with this ID doesn't exist");
        }

        $address->setAttributes($data);
        $result = $addressRepository->update($address);

        return $result;
    }
}