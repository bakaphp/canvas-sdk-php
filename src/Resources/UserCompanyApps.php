<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Resources;

use Kanvas\Sdk\Resources;
use Kanvas\Sdk\Traits\CrudOperationsTrait;

class UserCompanyApps extends Resources
{
    const RESOURCE_NAME = 'users-companies-apps';

    use CrudOperationsTrait;
}
