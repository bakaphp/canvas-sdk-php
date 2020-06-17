<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Resources;

use Kanvas\Sdk\Resources;
use Kanvas\Sdk\Traits\CrudOperationsTrait;

class CustomFields extends Resources
{
    const RESOURCE_NAME = 'custom-fields';

    use CrudOperationsTrait;
}
