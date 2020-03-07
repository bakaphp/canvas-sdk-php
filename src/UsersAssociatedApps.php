<?php

namespace Kanvas\Sdk;

use Kanvas\Sdk\Api\Operations\All;
use Kanvas\Sdk\Api\Operations\Create;
use Kanvas\Sdk\Api\Operations\Delete;
use Kanvas\Sdk\Api\Operations\Update;
use Kanvas\Sdk\Api\Operations\Retrieve;
use Kanvas\Sdk\Api\Resource;

class UsersAssociatedApps extends Resource
{
    const OBJECT_NAME = 'users-associated-apps';

    use All;
    use Create;
    use Delete;
    use Update;
    use Retrieve;
}
