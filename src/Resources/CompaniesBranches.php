<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Resources;

use Kanvas\Sdk\Contracts\CrudOperationsTrait;
use Kanvas\Sdk\Resources;

class CompaniesBranches extends Resources
{
    const RESOURCE_NAME = 'companies-branches';

    use CrudOperationsTrait;
}
