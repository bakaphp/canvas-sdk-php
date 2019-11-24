<?php

declare(strict_types=1);

namespace Canvas\Resources;

use Canvas\Api\ApiOperations\Auth as AuthTrait;
use Canvas\Api\ApiResource;

class Auth extends ApiResource
{
    const OBJECT_NAME = 'auth';

    use AuthTrait;
}
