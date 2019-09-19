<?php

declare(strict_types=1);

namespace Canvas\Resources;

use Canvas\ApiOperations\All;
use Canvas\ApiResource;

class Users extends ApiResource
{
    const OBJECT_NAME = "users";

    use ApiOperations\All;

}
