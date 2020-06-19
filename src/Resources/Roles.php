<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Resources;

use Kanvas\Sdk\Resources;
use Kanvas\Sdk\Contracts\CrudOperationsTrait;

class Roles extends Resources
{
    const RESOURCE_NAME = 'roles';
    const DEFAULT_ACL_APP_ID = 1;
    const DEFAULT_ACL_COMPANY_ID = 1;

    use CrudOperationsTrait;

    /**
     * Get App Id by its key.
     *
     * @param string $key
     *
     * @return object
     */
    public static function getUserRole(int $appId)
    {
        $currentCompanyId = Users::getSelf()['default_company'];
        return self::findFirst(null, ['conditions' => ["companies_id:{$currentCompanyId}", "apps_id:{$appId}", 'is_deleted:0']]);
    }

    /**
     * Get Role by name.
     *
     * @param string $role
     * @param int $currentCompanyId
     * @param string $appKey
     *
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

        $currentAppRole = current(self::find([
            'conditions' => [
                "name:{$role}",
                "companies_id:{$currentCompanyId}",
                "apps_id:{$app->id}"
            ]]));

        if ($currentAppRole instanceof KanvasObject) {
            return $currentAppRole;
        }

        return current(self::find([
            'conditions' => [
                'companies_id:' . self::DEFAULT_ACL_COMPANY_ID,
                'apps_id:' . self::DEFAULT_ACL_APP_ID
            ]]));
    }

    /**
     * Get the entity by its name.
     *
     * @param string $name
     *
     * @return Roles
     */
    public static function getByName(string $name, int $currentCompanyId, string $appKey)
    {
        $appsId = Apps::getIdByKey($appKey);

        $role = self::findFirst(null,[
            'conditions' => [
                "name:{$name}",
                "apps_id:{$appsId}",
                "companies_id:{$currentCompanyId}",
                'is_deleted:0'
            ]]));

        if (!$role instanceof KanvasObject) {
            $role = self::findFirst(null,[
                'conditions' => [
                    "name:{$name}",
                    "apps_id:{$appsId}",
                    'companies_id:' . self::DEFAULT_ACL_COMPANY_ID,
                    'is_deleted:0'
                ]]));
        }

        if (!$role instanceof KanvasObject) {
            $role = self::findFirst(null,[
                'conditions' => [
                    "name:{$name}",
                    'apps_id:' . self::DEFAULT_ACL_APP_ID,
                    "companies_id:{$currentCompanyId}",
                    'is_deleted:0'
                ]]));
        }

        if (!$role instanceof KanvasObject) {
            $role = self::findFirst(null,[
                'conditions' => [
                    "name:{$name}",
                    'apps_id:' . self::DEFAULT_ACL_APP_ID,
                    'companies_id:' . self::DEFAULT_ACL_COMPANY_ID,
                    'is_deleted:0'
                ]]));
        }

        return $role;
    }
}
