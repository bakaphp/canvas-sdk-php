<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Resources;

use Kanvas\Sdk\Resources;
use Kanvas\Sdk\Traits\CrudOperationsTrait;

class UserWebhooks extends Resources
{
    const RESOURCE_NAME = 'user-webhook';

    use CrudOperationsTrait;
}
