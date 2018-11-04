<?php

namespace Rest\controllers;

use \Rest\models\Address;
use Rest\Utils\JsonResponse;
use Rest\Utils\Request;
use \Rest\views\JsonView;

class AddressController extends ContainerAwareController
{
    public function actionGet($id = null)
    {
        $view = new JsonView();

        $addressRepository = $this->get('app.repository.address');

        if (null === $id) {
            $addressCollection = $addressRepository->findAll();
            return $view->renderJson(JsonResponse::HTTP_OK, $addressCollection);
        }

        $address = $addressRepository->find($id);

        if (!$address) {
            return $view->renderResourceNotFound();
        }

        return $view->renderJson(JsonResponse::HTTP_OK, $address);
    }

    public function actionCreate(Request $request)
    {
        $jsonData = json_decode($request->getBody());

        $address = new Address($jsonData);

        $addressRepository = $this->get('app.repository.address');
        $view = new JsonView();

        if ($address->validate()) {
            $addressRepository->add($address);
            return $view->render(201, array('success' => '1'));
        } else {
            return $view->render(400, array('error' => $address->getValidateError()));
        }
    }

    public function update($id, Request $request)
    {
        $view = new JsonView();
        $addressRepository = $this->get('app.repository.address');

        $address = $addressRepository->find($id);
        if (!$address) {
            return $view->render(400, "Adress with ID $id doesn't exist");
        }

        $address->setAttributes($data);
        $addressRepository->update($address);

        return $view->render(200, array("success" => "1"));
    }
}