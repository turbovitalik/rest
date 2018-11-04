<?php

use Rest\Utils\Request;
use Rest\Utils\Router;

require 'vendor/autoload.php';

$dotenv = new \Dotenv\Dotenv(__DIR__);
$dotenv->load();

$container = new \Pimple\Container();

$container['app.database.connection'] = function ($c) {
    return new \Rest\Utils\Database\Connection();
};

$container['app.repository.address'] = function ($c) {
    return new \Rest\Repository\AddressRepository($c['app.database.connection']->getConnection());
};

$container['app.router'] = function ($c) {
    return new Router($c);
};

$container['app.request'] = function ($c) {
    return new Request();
};

$api = new Rest\Api($container);

$api->run();

