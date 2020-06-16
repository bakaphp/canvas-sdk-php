<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Resources;

use Kanvas\Sdk\Resources;
use Kanvas\Sdk\Traits\CrudOperationsTrait;

class Subscription extends Resources
{
    const RESOURCE_NAME = 'subscriptions';

    use CrudOperationsTrait;
}
