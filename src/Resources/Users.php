<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Resources;

use Kanvas\Sdk\Resources;
use Kanvas\Sdk\Traits\CrudOperationsTrait;

class Users extends Resources
{
    const RESOURCE_NAME = 'users';
    const CANVAS_PATH = 'Canvas\Models\Users';

    use CrudOperationsTrait;

    /**
     * Get the default company of the the current user.
     *
     * @return Users
     */
    public static function getSelf() : array
    {
        return self::findFirst(0);
    }

    /**
     * Get the current User's sources.
     *
     * @return KanvasObject
     */
    public static function getSession() : array
    {
        $user = self::getSelf();
        return Sessions::findFirst(null, ['conditions' => ["users_id:{$user['id']}"]]);
    }

    /**
     * Get the current User's sources.
     *
     * @return array
     */
    public static function getSessions() : array
    {
        $user = self::getSelf();
        return Sessions::find(['conditions' => ["users_id:{$user['id']}"]]);
    }

    /**
     * Get the current User's sources.
     *
     * @return array
     */
    public static function getConfig() : array
    {
        $user = self::getSelf();
        return UserConfig::find(['conditions' => ["users_id:{$user['id']}"]]);
    }

    /**
     * Get the current User's sources.
     *
     * @return array
     */
    public static function getSources() : array
    {
        $user = self::getSelf();
        return UserLinkedSources::find(['conditions' => ["users_id:{$user['id']}"]]);
    }

    /**
     * Get the current Users Session.
     *
     * @return KanvasObject
     */
    public static function getDefaultCompany() : array
    {
        $user = self::getSelf();
        return Companies::findFirst($user['default_company']);
    }

    /**
     * Get the company Id.
     *
     * @return void
     */
    public static function getCompanyId() : int
    {
        return (int) self::getSelf()['default_company'];
    }

    /**
     * Get the current company of the the current user.
     *
     * @return KanvasObject
     */
    public static function getCurrentCompany() : array
    {
        return self::getDefaultCompany();
    }

    /**
     * Get all the user's subscriptions.
     *
     * @return array
     */
    public static function getAllSubscriptions()
    {
        $user = self::getSelf();
        $appsId = Apps::getIdByKey(self::getClient()->getApiKey());
        return Subscription::find([
            'conditions' => [
                "user_id:{$user['id']}",
                "apps_id:{$appsId}"],
            'sort' => 'id|desc'
        ]);
    }

    /**
     * Get all the user's companies.
     *
     * @return array
     */
    public static function getCompanies() : array
    {
        $user = self::getSelf();
        $appsId = Apps::getIdByKey(self::getClient()->getApiKey());
        return UsersAssociatedApps::find([
            'conditions' => [
                "users_id:{$user['id']}",
                "apps_id:{$appsId}"]
        ]);
    }

    /**
     * Get all the user's apps.
     *
     * @return array
     */
    public function getApps() : array
    {
        $user = self::getSelf();
        return UsersAssociatedApps::find(['conditions' => ["users_id:{$user['id']}"]]);
    }

    /**
     * Get User Webhooks.
     *
     * @return array
     */
    public static function getUserWebhook() : array
    {
        $user = self::getSelf();
        return UserWebhooks::find(['conditions' => ["users_id:{$user['id']}"]]);
    }

    /**
     * Get User Files.
     *
     * @return KanvasObject
     */
    public static function getFiles() : array
    {
        $user = self::getSelf();
        $appsId = Apps::getIdByKey(self::getClient()->getApiKey());
        $systemModule = SystemModules::getSystemModuleByModelName(self::CANVAS_PATH, (int)$appsId);
        return FileSystemEntities::find([
            'conditions' => [
                "entity_id:{$user['id']}",
                "system_modules_id:{$systemModule['id']}"
            ]]);
    }

    /**
     * Get User Photo.
     *
     * @return KanvasObject
     */
    public static function getPhoto() : array
    {
        $user = self::getSelf();
        $appsId = Apps::getIdByKey(self::getClient()->getApiKey());
        $systemModule = SystemModules::getSystemModuleByModelName(self::CANVAS_PATH, (int)$appsId);
        return FileSystemEntities::findFirst(null, [
            'conditions' => [
                "entity_id:{$user['id']}",
                "system_modules_id:{$systemModule['id']}"
            ],
            'sort' => 'id|desc'
        ]);
    }

    /**
     * Get user's roles.
     *
     * @return array
     */
    public static function getRoles() : array
    {
        $rolesArray = [];
        $user = self::getSelf();
        // Get all user roles
        $userRoles = UserRoles::find(['conditions' => ["users_id:{$user['id']}"]]);
        // Get all the roles by id and push them to an array
        foreach ($userRoles as $userRole) {
            $rolesArray[] = Roles::findFirst(null, ['conditions' => ["id:{$userRole['roles_id']}"]]);
        }

        return $rolesArray;
    }

    /**
     * Get user role.
     *
     * @return KanvasObject
     */
    public static function getUserRole() : array
    {
        $user = self::getSelf();
        $appsId = Apps::getIdByKey(self::getClient()->getApiKey());
        $userRole = UserRoles::findFirst(null, ['conditions' => ["users_id:{$user['id']}", "apps_id:{$appsId}", 'companies_id:' . self::getCurrentCompany()['id']]]);

        if (!empty($userRole)) {
            return $userRole;
        }

        return UserRoles::findFirst(null, ['conditions' => ["users_id:{$user['id']}", 'apps_id:' . Roles::DEFAULT_ACL_APP_ID, 'companies_id:' . self::getCurrentCompany()['id']]]);
    }

    /**
     * Get user role.
     *
     * @return array
     */
    public static function getPermissions() : array
    {
        $user = self::getSelf();
        $appsId = Apps::getIdByKey(self::getClient()->getApiKey());
        return UserRoles::find(['conditions' => ["users_id:{$user['id']}", "apps_id:{$appsId}", 'companies_id:' . self::getCurrentCompany()['id']]]);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public static function getSource() : string
    {
        return 'users';
    }

    /**
     * Get the User key for redis.
     *
     * @return string
     */
    public static function getKey() : int
    {
        return (int)self::getSelf()['id'];
    }
}
