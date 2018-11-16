<?php

namespace Rest;

use Pimple\Container;
use Rest\Utils\JsonResponse;
use Rest\Utils\Request;

class Api
{
    protected $method;
    protected $param;
    protected $data;

    /**
     * Api constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function run()
    {
        try {
            $request = $this->container['app.request'];
            $response = $this->handle($request);
        } catch (\Exception $e) {
            $response = new JsonResponse();
            $response->setContent($e->getMessage());
            $response->setStatus(JsonResponse::HTTP_BAD_REQUEST);
        }

        $response->send();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function handle(Request $request)
    {
        $router = $this->container['app.router'];

        $response = $router->handleRequest($request);

        return $response;
    }
}
