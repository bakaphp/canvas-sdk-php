<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Resources;

use Kanvas\Sdk\Resources;
use Kanvas\Sdk\Traits\CrudOperationsTrait;

class CompaniesCustomFields extends Resources
{
    const RESOURCE_NAME = 'companies-custom-fields';

    use CrudOperationsTrait;
}
