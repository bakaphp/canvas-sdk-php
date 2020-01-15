<?php

use Kanvas\Sdk\Bootstrap\IntegrationTests;

require_once __DIR__ . '/../../src/Core/functions.php';

$bootstrap = new IntegrationTests();
$bootstrap->setup();

return $bootstrap->run();
