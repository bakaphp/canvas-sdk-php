<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Resources;

use Kanvas\Sdk\Resources;
use Kanvas\Sdk\Traits\CrudOperationsTrait;

class UsersAssociatedCompanies extends Resources
{
    const RESOURCE_NAME = 'users-associated-companies';

    use CrudOperationsTrait;
}
