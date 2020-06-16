<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Resources;

use Kanvas\Sdk\Resources;
use Kanvas\Sdk\Traits\CrudOperationsTrait;

class Users extends Resources
{
    const RESOURCE_NAME = 'users';

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
        return current(Sessions::find(['conditions' => ["users_id:{$user['id']}"]]));
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
    public function getConfig() : array
    {
        $user = self::getSelf();
        return UserConfig::find(['conditions' => ["users_id:{$user['id']}"]]);
    }

    /**
     * Get the current User's sources.
     *
     * @return array
     */
    public function getSources() : array
    {
        $user = self::getSelf();
        return UserLinkedSources::find(['conditions' => ["users_id:{$user->id}"]]);
    }

    /**
     * Get the current Users Session.
     *
     * @return KanvasObject
     */
    public function getDefaultCompany() : array
    {
        $user = self::getSelf();
        return Companies::findFirst($user['default_company']);
    }

    /**
     * Get the company Id.
     *
     * @return void
     */
    public function getCompanyId() : int
    {
        return (int) self::getSelf()->default_company;
    }

    /**
     * Get the current company of the the current user.
     *
     * @return KanvasObject
     */
    public function getCurrentCompany() : Companies
    {
        return $this->getDefaultCompany();
    }

    /**
     * Get all the user's subscriptions.
     *
     * @return array
     */
    public function getAllSubscriptions() : array
    {
        $user = self::getSelf();
        $appsId = Apps::getIdByKey(Kanvas::getApiKey());
        return Subscription::find([
            'conditions' => [
                "user_id:{$user->id}",
                "apps_id:{$appsId}"],
            'sort' => 'id|desc'
        ]);
    }

    /**
     * Get all the user's companies.
     *
     * @return array
     */
    public function getCompanies() : array
    {
        $user = self::getSelf();
        $appsId = Apps::getIdByKey(Kanvas::getApiKey());
        return UsersAssociatedApps::find([
            'conditions' => [
                "users_id:{$user->id}",
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
        return UsersAssociatedApps::find(['conditions' => ["users_id:{$user->id}"]]);
    }

    /**
     * Get User Webhooks.
     *
     * @return array
     */
    public function getUserWebhook() : array
    {
        $user = self::getSelf();
        return UserWebhooks::find(['conditions' => ["users_id:{$user->id}"]]);
    }

    /**
     * Get User Files.
     *
     * @return KanvasObject
     */
    public function getFiles() : KanvasObject
    {
        $user = self::getSelf();
        $appsId = Apps::getIdByKey(Kanvas::getApiKey());
        $systemModule = SystemModules::getSystemModuleByModelName(self::CANVAS_PATH, (int)$appsId);
        return current(FileSystemEntities::find(['conditions' => ["entity_id:{$user->id}", "system_modules_id:{$systemModule->id}"]]));
    }

    /**
     * Get User Photo.
     *
     * @return KanvasObject
     */
    public function getPhoto() : KanvasObject
    {
        $user = self::getSelf();
        $appsId = Apps::getIdByKey(Kanvas::getApiKey());
        $systemModule = SystemModules::getSystemModuleByModelName(self::CANVAS_PATH, (int)$appsId);
        return current(FileSystemEntities::find(['conditions' => ["entity_id:{$user->id}", "system_modules_id:{$systemModule->id}"]]));
    }

    /**
     * Get user's roles.
     *
     * @return array
     */
    public function getRoles() : array
    {
        $rolesArray = [];
        $user = self::getSelf();
        // Get all user roles
        $userRoles = UserRoles::find(['conditions' => ["users_id:{$user->id}"]]);
        // Get all the roles by id and push them to an array
        foreach ($userRoles as $userRole) {
            $rolesArray[] = current(Roles::find(['conditions' => ["id:{$userRole->roles_id}"]]));
        }

        return $rolesArray;
    }

    /**
     * Get user role.
     *
     * @return KanvasObject
     */
    public function getUserRole() : KanvasObject
    {
        $user = self::getSelf();
        $appsId = Apps::getIdByKey(Kanvas::getApiKey());
        $userRole = current(UserRoles::find(['conditions' => ["users_id:{$user->id}", "apps_id:{$appsId}", 'companies_id:' . $this->getCurrentCompany()->id]]));

        if ($userRole  instanceof KanvasObject) {
            return $userRole;
        }

        return current(UserRoles::find(['conditions' => ["users_id:{$user->id}", 'apps_id:' . Roles::DEFAULT_ACL_APP_ID, 'companies_id:' . $this->getCurrentCompany()->id]]));
    }

    /**
     * Get user role.
     *
     * @return array
     */
    public function getPermissions() : array
    {
        $user = self::getSelf();
        $appsId = Apps::getIdByKey(Kanvas::getApiKey());
        return UserRoles::find(['conditions' => ["users_id:{$user->id}", "apps_id:{$appsId}", 'companies_id:' . $this->getCurrentCompany()->id]]);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource() : string
    {
        return 'users';
    }

    /**
     * Set hashtable settings table, userConfig ;).
     *
     * @return void
     */
    private function createSettingsModel() : void
    {
        $this->settingsModel = new UserConfig();
    }

    /**
     * Get the User key for redis.
     *
     * @return string
     */
    public function getKey() : int
    {
        return $this->id;
    }
}
