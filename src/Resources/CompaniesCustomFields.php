<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Resources;

use Kanvas\Sdk\Contracts\CrudOperationsTrait;
use Kanvas\Sdk\Resources;

class CompaniesCustomFields extends Resources
{
    const RESOURCE_NAME = 'companies-custom-fields';

    use CrudOperationsTrait;
}