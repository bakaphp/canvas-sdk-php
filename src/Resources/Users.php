<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Resources;

use Kanvas\Sdk\Contracts\CrudOperationsTrait;
use Kanvas\Sdk\Resources;

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
    public static function getSelf()
    {
        return self::findFirst(0);
    }

    /**
     * Get the current User's sources.
     *
     * @return KanvasObject
     */
    public function getSession() : object
    {
        return Sessions::findFirst(null, ['conditions' => ["users_id:{$this->id}"]]);
    }

    /**
     * Get the current User's sources.
     *
     * @return array
     */
    public function getSessions() : array
    {
        return Sessions::find(['conditions' => ["users_id:{$this->id}"]]);
    }

    /**
     * Get the current User's sources.
     *
     * @return array
     */
    public function getConfig() : array
    {
        return UserConfig::find(['conditions' => ["users_id:{$this->id}"]]);
    }

    /**
     * Get the current User's sources.
     *
     * @return array
     */
    public function getSources() : array
    {
        return UserLinkedSources::find(['conditions' => ["users_id:{$this->id}"]]);
    }

    /**
     * Get the current Users Session.
     *
     * @return KanvasObject
     */
    public function getDefaultCompany() : object
    {
        return Companies::findFirst($this->default_company);
    }

    /**
     * Get the company Id.
     *
     * @return void
     */
    public function getCompanyId() : int
    {
        return (int) $this->default_company;
    }

    /**
     * Get the current company of the the current user.
     *
     * @return KanvasObject
     */
    public function getCurrentCompany() : object
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
        $appsId = Apps::getIdByKey(self::getClient()->getApiKey());
        return Subscription::find([
            'conditions' => [
                "user_id:{$this->id}",
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
        $appsId = Apps::getIdByKey(self::getClient()->getApiKey());
        return UsersAssociatedApps::find([
            'conditions' => [
                "users_id:{$this->id}",
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
        return UsersAssociatedApps::find(['conditions' => ["users_id:{$this->id}"]]);
    }

    /**
     * Get User Webhooks.
     *
     * @return array
     */
    public function getUserWebhook() : array
    {
        return UserWebhooks::find(['conditions' => ["users_id:{$this->id}"]]);
    }

    /**
     * Get User Files.
     *
     * @return KanvasObject
     */
    public function getFiles() : array
    {
        $appsId = Apps::getIdByKey(self::getClient()->getApiKey());
        $systemModule = SystemModules::getSystemModuleByModelName(self::CANVAS_PATH, (int)$appsId);
        return FileSystemEntities::find([
            'conditions' => [
                "entity_id:{$this->id}",
                "system_modules_id:{$systemModule->id}"
            ]]);
    }

    /**
     * Get User Photo.
     *
     * @return KanvasObject
     */
    public function getPhoto() : object
    {
        $appsId = Apps::getIdByKey(self::getClient()->getApiKey());
        $systemModule = SystemModules::getSystemModuleByModelName(self::CANVAS_PATH, (int)$appsId);
        return FileSystemEntities::findFirst(null, [
            'conditions' => [
                "entity_id:{$this->id}",
                "system_modules_id:{$systemModule->id}"
            ],
            'sort' => 'id|desc'
        ]);
    }

    /**
     * Get user's roles.
     *
     * @return array
     */
    public function getRoles() : array
    {
        $rolesArray = [];
        // Get all user roles
        $userRoles = UserRoles::find(['conditions' => ["users_id:{$this->id}"]]);
        // Get all the roles by id and push them to an array
        foreach ($userRoles as $userRole) {
            $rolesArray[] = Roles::findFirst(null, ['conditions' => ["id:{$userRole->roles_id}"]]);
        }

        return $rolesArray;
    }

    /**
     * Get user role.
     *
     * @return KanvasObject
     */
    public function getUserRole() : object
    {
        $appsId = Apps::getIdByKey(self::getClient()->getApiKey());
        $userRole = UserRoles::findFirst(null, ['conditions' => ["users_id:{$this->id}", "apps_id:{$appsId}", 'companies_id:' . $this->getCurrentCompany()->id]]);

        if (!empty($userRole)) {
            return $userRole;
        }

        return UserRoles::findFirst(null, ['conditions' => ["users_id:{$this->id}", 'apps_id:' . Roles::DEFAULT_ACL_APP_ID, 'companies_id:' . $this->getCurrentCompany()->id]]);
    }

    /**
     * Get user role.
     *
     * @return array
     */
    public function getPermissions() : array
    {
        $appsId = Apps::getIdByKey(self::getClient()->getApiKey());
        return UserRoles::find(['conditions' => ["users_id:{$this->id}", "apps_id:{$appsId}", 'companies_id:' . $this->getCurrentCompany()->id]]);
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
    public static function getId() : int
    {
        return (int)self::getSelf()->id;
    }
}
