<?php

namespace Rest\controllers;

use Rest\models\Address;
use Rest\Repository\AddressRepository;
use Rest\Utils\JsonResponse;
use Rest\Utils\Request;

class AddressController extends JsonController
{
    public function actionGet($id = null)
    {
        $addressRepository = $this->get('app.repository.address');

        if (null === $id) {
            $addressCollection = $addressRepository->findAll();
            return $this->view->renderJson(JsonResponse::HTTP_OK, $addressCollection);
        }

        $address = $addressRepository->find($id);

        if (!$address) {
            return $this->view->renderResourceNotFound();
        }

        return $this->view->renderJson(JsonResponse::HTTP_OK, $address);
    }

    public function actionCreate(Request $request)
    {
        $data = $request->getBody();

        /** @var AddressRepository $addressRepository */
        $addressRepository = $this->get('app.repository.address');

        $address = new Address($data);

        if ($address->isValid()) {
            $addressRepository->save($address);
            return $this->view->renderJson(JsonResponse::HTTP_OK, $address);
        }

        return $this->view->renderJson(JsonResponse::HTTP_BAD_REQUEST, $e->getMessage());
    }

    public function actionUpdate($id, Request $request)
    {
        $data = $request->getBody();

        $addressRepository = $this->get('app.repository.address');

        $address = $addressRepository->find($id);

        if (!$address) {
            return $this->view->renderResourceNotFound();
        }

        try {
            $address->populateWith($data);
            $addressRepository->save($address);
        } catch (\Exception $e) {
            return $this->view->renderJson(JsonResponse::HTTP_BAD_REQUEST, $e->getMessage());
        }

        return $this->view->renderJson(JsonResponse::HTTP_OK, $address);
    }
}