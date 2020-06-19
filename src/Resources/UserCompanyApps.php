<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Resources;

use Kanvas\Sdk\Contracts\CrudOperationsTrait;
use Kanvas\Sdk\Resources;

class UserCompanyApps extends Resources
{
    const RESOURCE_NAME = 'users-companies-apps';

    use CrudOperationsTrait;
}
