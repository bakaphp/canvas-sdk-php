<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Resources;

use Kanvas\Sdk\Resources;
use Kanvas\Sdk\Traits\CrudOperationsTrait;

class Apps extends Resources
{
    const RESOURCE_NAME = 'apps';
    const CANVAS_DEFAULT_APP_ID = 1;
    const CANVAS_DEFAULT_APP_NAME = 'Default';
    const APP_DEFAULT_ROLE_SETTING = 'default_admin_role';

    use CrudOperationsTrait;

    /**
     * Get App Id by its key.
     *
     * @param string $key
     *
     * @return int
     */
    public static function getIdByKey(string $key)
    {
        return self::findFirst(null, ['conditions' => ["key:{$key}", 'is_deleted:0']])['id'];
    }

    /**
     * Get App by its key.
     *
     * @param string $key
     *
     * @return int
     */
    public static function findFirstByKey(string $key)
    {
        return self::findFirst(null, ['conditions' => ["key:{$key}", 'is_deleted:0']]);
    }

    /**
     * You can only get 2 variations or default in DB or the api app.
     *
     * @param string $name
     *
     * @return KanvasObject
     */
    public static function getACLApp(string $name, string $key)
    {
        if (trim($name) == self::CANVAS_DEFAULT_APP_NAME) {
            return Apps::findFirst(1);
        }

        return self::findFirstByKey($key);
    }
}
