<?php

namespace Rest\Utils;

use Rest\ApiException;
use Rest\Controllers\ContainerAwareController;
use Rest\Controllers\JsonController;
use Rest\views\JsonView;

class Router
{
    /**
     * @var \Pimple\Container
     */
    protected $container;

    /**
     * Router constructor.
     * @param \Pimple\Container $container
     */
    public function __construct(\Pimple\Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @throws ApiException
     */
    public function handleRequest(Request $request)
    {
        $parts = explode('/', $request->getUri());

        $idParam = isset($parts[2]) ? $parts[2] : null;

        $requestData = $request->getBody();

        $entityName = ucfirst(strtolower($parts[1]));

        $controllerClass = 'Rest\\Controllers\\' . $entityName . 'Controller';

        if (!class_exists($controllerClass)) {
            throw new ApiException(404, "Resource not found");
        }

        //TODO: do this based on content-type headers
        $view = new JsonView();

        $controller = new $controllerClass();

        if ($controller instanceof JsonController) {
            $controller->setContainer($this->container);
            $controller->setView($view);
        }

        switch ($request->getMethod()) {
            case 'GET':
                $response = $controller->actionGet($idParam);
                break;

            case 'POST':
                if ($idParam) {
                    throw new ApiException(400);
                }

                $request = new RequestMock();

                $response = $controller->actionCreate($request);
                break;

            case 'PUT':
                if (!$requestData || !$idParam) {
                    throw new ApiException(400, "Data for updating isn't set!");
                }

                $request = new RequestMock();
                $response = $controller->actionUpdate($idParam, $request);
                break;
        }

        return $response;
    }
}
