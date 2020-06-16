<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Resources;

use Kanvas\Sdk\Resources;
use Kanvas\Sdk\Traits\CrudOperationsTrait;

/**
 * @todo Implement relationship functions using the new http client of the SDK.
 */
class Companies extends Resources
{
    const RESOURCE_NAME = 'companies';

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
    public static function getUser() : array
    {
        $user = self::getSelf();
        return current(Sessions::find(['conditions' => ["users_id:{$user['id']}"]]));
    }

    /**
     * Get the current User's sources.
     *
     * @return array
     */
    public static function getBranches() : array
    {
        $user = self::getSelf();
        return Sessions::find(['conditions' => ["users_id:{$user['id']}"]]);
    }

    /**
     * Get the current User's sources.
     *
     * @return array
     */
    public function getDefaultBranch() : array
    {
        $user = self::getSelf();
        return UserConfig::find(['conditions' => ["users_id:{$user['id']}"]]);
    }

    /**
     * Get the current User's sources.
     *
     * @return array
     */
    public function getFields() : array
    {
        $user = self::getSelf();
        return UserLinkedSources::find(['conditions' => ["users_id:{$user->id}"]]);
    }

    /**
     * Get the current Users Session.
     *
     * @return KanvasObject
     */
    public function getCustomFields() : array
    {
        $user = self::getSelf();
        return Companies::findFirst($user['default_company']);
    }

    /**
     * Get the company Id.
     *
     * @return void
     */
    public function getUsersAssociatedCompanies() : int
    {
        return (int) self::getSelf()->default_company;
    }

    /**
     * Get the current company of the the current user.
     *
     * @return KanvasObject
     */
    public function getUsersAssociatedApps() : Companies
    {
        return $this->getDefaultCompany();
    }

    /**
     * Get all the user's subscriptions.
     *
     * @return array
     */
    public function getUsersAssociatedByApps() : array
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
    public function getBranch() : array
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
    public function getApp() : array
    {
        $user = self::getSelf();
        return UsersAssociatedApps::find(['conditions' => ["users_id:{$user->id}"]]);
    }

    /**
     * Get User Webhooks.
     *
     * @return array
     */
    public function getApps() : array
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
     * Get User Files.
     *
     * @return KanvasObject
     */
    public function getCompaniesAssoc() : KanvasObject
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
    public function getUsers() : array
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
    public function getSubscription() : KanvasObject
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
    public function getSubscriptions() : array
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
        return 'companies';
    }

    /**
     * Set hashtable settings table, userConfig ;).
     *
     * @return void
     */
    private function getUserWebhooks() : void
    {
        $this->settingsModel = new UserConfig();
    }
}
