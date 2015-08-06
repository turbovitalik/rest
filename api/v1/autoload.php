<?php

require 'Autoloader.php';

$srcDir = __DIR__ . '/src';

$autoloader = new Autoloader();
$autoloader->addNamespace('Rest', $srcDir);
$autoloader->register();