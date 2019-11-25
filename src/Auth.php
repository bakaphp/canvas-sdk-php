<?php

declare(strict_types=1);

namespace Canvas;

use Canvas\Api\Operations\Auth as AuthTrait;
use Canvas\Api\Resource;

class Auth extends Resource
{
    const OBJECT_NAME = 'auth';

    use AuthTrait;
}
