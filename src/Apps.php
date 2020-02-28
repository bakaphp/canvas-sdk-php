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
    const CANVAS_DEFAULT_APP_ID = 1;
    const CANVAS_DEFAULT_APP_NAME = 'Default';
    const APP_DEFAULT_ROLE_SETTING = 'default_admin_role';

    use All;
    use Retrieve;


    /**
     * Get App Id by its key
     *
     * @param string $key
     * @return int
     */
    public static function getIdByKey(string $key)
    {
        return current(self::all([], ['conditions'=> ["key:{$key}","is_deleted:0"]]))->id;
    }

    /**
     * Get App by its key
     *
     * @param string $key
     * @return int
     */
    public static function findFirstByKey(string $key)
    {
        return current(self::all([], ['conditions'=> ["key:{$key}","is_deleted:0"]]));
    }

    /**
     * You can only get 2 variations or default in DB or the api app.
     *
     * @param string $name
     * @return KanvasObject
     */
    public static function getACLApp(string $name, string $key)
    {
        if (trim($name) == self::CANVAS_DEFAULT_APP_NAME) {
            return Apps::retrieve('1', [], []);
        }

        return self::findFirstByKey($key);
    }
}
