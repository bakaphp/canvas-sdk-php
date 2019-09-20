<?php

declare(strict_types=1);

namespace Canvas\Resources;

use Canvas\Api\ApiOperations\All;
use Canvas\Api\ApiOperations\Create;
use Canvas\Api\ApiOperations\Delete;
use Canvas\Api\ApiOperations\Update;
use Canvas\Api\ApiOperations\Retrieve;
use Canvas\Api\ApiResource;

class Companies extends ApiResource
{
    const OBJECT_NAME = "companies";

    use All;
    use Create;
    use Delete;
    use Update;
    use Retrieve;


}
