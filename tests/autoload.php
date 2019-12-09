<?php

// use Dotenv\Dotenv;
use Phalcon\Loader;
use function Kanvas\Sdk\Core\appPath;

// Register the auto loader
require __DIR__ . '/../src/Core/functions.php';

$loader = new Loader();
$namespaces = [
    'Kanvas\Sdk' => appPath('/src'),
    'Kanvas\Sdk\Tests' => appPath('/tests'),
];

$loader->registerNamespaces($namespaces);

$loader->register();

/**
 * Composer Autoloader.
 */
require appPath('vendor/autoload.php');

// Load environment
// (new Dotenv(appPath()))->overload();
