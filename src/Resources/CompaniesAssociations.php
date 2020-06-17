<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Resources;

use Kanvas\Sdk\Resources;
use Kanvas\Sdk\Traits\CrudOperationsTrait;

class CompaniesAssociations extends Resources
{
    const RESOURCE_NAME = 'companies-associations';

    use CrudOperationsTrait;
}
