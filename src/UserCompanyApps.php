<?php

declare(strict_types=1);

namespace Kanvas\Sdk;

use Kanvas\Sdk\Api\Operations\All;
use Kanvas\Sdk\Api\Operations\Create;
use Kanvas\Sdk\Api\Operations\Delete;
use Kanvas\Sdk\Api\Operations\Update;
use Kanvas\Sdk\Api\Operations\Retrieve;
use Kanvas\Sdk\Api\Resource;
use Kanvas\Sdk\Util\Util;

/**
 * Filesystem Resource.
 */
class UserCompanyApps extends Resource
{
    const OBJECT_NAME = 'users-companies-apps';

    use All;
    use Create;
    use Delete;
    use Update;
    use Retrieve;
}
