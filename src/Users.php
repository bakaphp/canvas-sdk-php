<?php

declare(strict_types=1);

namespace Canvas;

use Canvas\Api\Operations\All;
use Canvas\Api\Operations\Create;
use Canvas\Api\Operations\Delete;
use Canvas\Api\Operations\Update;
use Canvas\Api\Operations\Retrieve;
use Canvas\Api\Resource;

class Users extends Resource
{
    const OBJECT_NAME = 'users';

    use All;
    use Create;
    use Delete;
    use Update;
    use Retrieve;
}
