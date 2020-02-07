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
 * Filesystem Resource
 */
class Apps extends Resource
{
    const OBJECT_NAME = 'apps';

    use All;
    use Retrieve;


    /**
     * Get App Id by its key
     *
     * @param string $key
     * @return void
     */
    public static function getIdByKey(string $key)
    {
        return current(self::all([], ['conditions'=> ["key:{$key}","is_deleted:0"]]));
    }
}
