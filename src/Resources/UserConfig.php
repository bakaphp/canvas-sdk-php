<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Resources;

use Kanvas\Sdk\Resources;
use Kanvas\Sdk\Traits\CrudOperationsTrait;

class UserConfig extends Resources
{
    const RESOURCE_NAME = 'users-config';

    use CrudOperationsTrait;
}
