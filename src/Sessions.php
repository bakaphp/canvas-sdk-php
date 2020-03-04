<?php

declare(strict_types=1);

namespace Kanvas\Sdk;

use Kanvas\Sdk\Api\Operations\All;
use Kanvas\Sdk\Api\Operations\Retrieve;
use Kanvas\Sdk\Api\Resource;

/**
 * System Modules Resource
 */
class Sessions extends Resource
{
    const OBJECT_NAME = 'sessions';

    use All;
    use Retrieve;
}
