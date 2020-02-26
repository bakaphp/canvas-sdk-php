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
use Exception;

/**
 * Filesystem Resource.
 */
class Roles extends Resource
{
    const OBJECT_NAME = 'roles';
    const DEFAULT_ACL_APP_ID = 1;
    const DEFAULT_ACL_COMPANY_ID = 1;

    use All;
    use Retrieve;

    /**
     * Get App Id by its key.
     *
     * @param string $key
     * @return object
     */
    public static function getUserRole(int $appId)
    {
        $currentCompanyId = Users::getSelf()->default_company;
        return current(self::all([], ['conditions' => ["companies_id:{$currentCompanyId}", "apps_id:{$appId}", 'is_deleted:0']]));
    }

    /**
     * Get Role by name.
     *
     * @param string $role
     * @param int $currentCompanyId
     * @param string $appKey
     * @return object
     */
    public static function getByAppName(string $role, int $currentCompanyId, string $appKey)
    {   
        if (strpos($role, '.') === false) {
            throw new Exception('ACL - We are expecting the app for this role');
        }

        $appRole = explode('.', $role);
        $role = $appRole[1];
        $appName = $appRole[0];

        //look for the app and set it
        if (!$app = Apps::getACLApp($appName, $appKey)) {
            throw new Exception('ACL - No app found for this role');
        }

        $currentAppRole = current(self::all([], [
            'conditions' => [
                "companies_id:{$currentCompanyId}", 
                "apps_id:{$app->id}"
        ]]));

        if ($currentAppRole instanceof KanvasObject) {
            return $currentAppRole;
        }

        // return current(self::all([], [
        //     'conditions' => [
        //         "companies_id:" . self::DEFAULT_ACL_COMPANY_ID, 
        //         "apps_id:" . self::DEFAULT_ACL_APP_ID
        // ]]));
    }
}
