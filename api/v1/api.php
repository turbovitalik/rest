<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'autoload.php';

$api = new Rest\Api();

$api->handle();	

