<?php

namespace Rest\controllers;

use \Rest\models\AddressRepository;
use \Rest\models\Address;
use \Rest\views\JsonView;

class AddressesController
{
    public function view($id = null)
    {
        $addressRepository = new AddressRepository();
        $view = new JsonView();

        if ($id) {
            $result = $addressRepository->find($id);
            if (!$result) {
                return $view->render(404, array(
                    "error" => "Address with ID $id doesn't exist"
                ));
            }

        } else {
            $result = $addressRepository->findAll();
        }

        return $view->render(200, $result);
    }

    public function create($data)
    {
        $address = new Address($data);
        $view = new JsonView();

        if ($address->validate()) {
            $addressRepository = new AddressRepository();
            $addressRepository->add($address);
            return $view->render(201, array('success' => '1'));
        } else {
            return $view->render(400, array('error' => $address->getValidateError()));
        }
    }

    public function update($id, $data)
    {
        $addressRepository = new AddressRepository();
        $view = new JsonView();

        $address = $addressRepository->find($id);
        if (!$address) {
            return $view->render(400, "Adress with ID $id doesn't exist");
        }

        $address->setAttributes($data);
        $addressRepository->update($address);

        return $view->render(200, array("success" => "1"));
    }
}