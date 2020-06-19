<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Resources;

use Kanvas\Sdk\Contracts\CrudOperationsTrait;
use Kanvas\Sdk\Resources;

class UsersAssociatedCompanies extends Resources
{
    const RESOURCE_NAME = 'users-associated-companies';

    use CrudOperationsTrait;
}
