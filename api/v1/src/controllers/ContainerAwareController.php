<?php

namespace Rest\Controllers;

use Pimple\Container;

class ContainerAwareController
{
    /**
     * @var Container
     */
    protected $container;

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
}