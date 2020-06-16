<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Resources;

use Kanvas\Sdk\Resources;
use Kanvas\Sdk\Traits\CrudOperationsTrait;

class Roles extends Resources
{
    const RESOURCE_NAME = 'roles';

    use CrudOperationsTrait;
}
