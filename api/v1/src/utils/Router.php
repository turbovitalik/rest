<?php

namespace Rest\Utils;

use Rest\ApiException;
use Rest\Controllers\ContainerAwareController;

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

        $controller = new $controllerClass();

        if ($controller instanceof ContainerAwareController) {
            $controller->setContainer($this->container);
        }

        switch ($request->getMethod()) {
            case 'GET':
                $response = $controller->actionGet($idParam);
                break;

            case 'POST':
//                if (!$requestData) {
//                    throw new ApiException(400, "Data for creating isn't set!");
//                }
                if ($idParam) {
                    throw new ApiException(400);
                }
                $response = $controller->actionCreate($request);
                break;

            case 'PUT':
                if (!$requestData || !$idParam) {
                    throw new ApiException(400, "Data for updating isn't set!");
                }
                $response = $controller->update($idParam, $request);
                break;
        }

        return $response;
    }

    public function runAction($controller, $method)
    {

    }
}