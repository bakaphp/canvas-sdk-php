<?php

declare(strict_types=1);

namespace Canvas\Resources;

use Canvas\ApiOperations\All;
use Canvas\ApiOperations\Create;
use Canvas\ApiOperations\Delete;
use Canvas\ApiOperations\Update;
use Canvas\ApiResource;

class Users extends ApiResource
{
    const OBJECT_NAME = "users";

    use All;
    use Create;
    use Delete;
    use Update;

}
