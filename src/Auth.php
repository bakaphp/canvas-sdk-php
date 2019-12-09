<?php

declare(strict_types=1);

namespace Kanvas\Sdk;

use Kanvas\Sdk\Api\Operations\Auth as AuthTrait;
use Kanvas\Sdk\Api\Resource;

class Auth extends Resource
{
    const OBJECT_NAME = 'auth';

    use AuthTrait;
}
