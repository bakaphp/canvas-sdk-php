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
use Kanvas\Sdk\Users;
use Kanvas\Sdk\Apps;

/**
 * Filesystem Resource
 */
class Roles extends Resource
{
    const OBJECT_NAME = 'roles';

    use All;
    use Retrieve;


    /**
     * Get App Id by its key
     *
     * @param string $key
     * @return object
     */
    public static function getUserRole(int $appId)
    {
        $currentCompanyId = Users::getSelf()->default_company;
        return current(self::all([], ['conditions'=> ["companies_id:{$currentCompanyId}","apps_id:{$appId}","is_deleted:0"]]));
    }
}
