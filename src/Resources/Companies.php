<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Resources;

use Kanvas\Sdk\Resources;
use Kanvas\Sdk\Traits\CrudOperationsTrait;

class Companies extends Resources
{
    const RESOURCE_NAME = 'companies';

    use CrudOperationsTrait;
}
