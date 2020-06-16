<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Resources;

use Kanvas\Sdk\Resources;
use Kanvas\Sdk\Traits\CrudOperationsTrait;

class UserLinkedSources extends Resources
{
    const RESOURCE_NAME = 'users-linked-sources';

    use CrudOperationsTrait;
}
