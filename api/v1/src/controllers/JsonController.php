<?php

namespace Rest\Controllers;

use Pimple\Container;
use Rest\views\JsonView;

class JsonController
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var JsonView
     */
    protected $view;

    /**
     * @param $serviceId string
     * @return mixed
     */
    public function get($serviceId)
    {
        return $this->container[$serviceId];
    }

    /**
     * @param Container $container
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param JsonView $view
     */
    public function setView(JsonView $view)
    {
        $this->view = $view;
    }
}
