<?php

declare(strict_types=1);

namespace Kanvas\Sdk;

use AutoMapperPlus\Test\Models\Issues\Issue33\User;
use Kanvas\Sdk\Api\Operations\All;
use Kanvas\Sdk\Api\Operations\Create;
use Kanvas\Sdk\Api\Operations\Delete;
use Kanvas\Sdk\Api\Operations\Update;
use Kanvas\Sdk\Api\Operations\Retrieve;
use Kanvas\Sdk\Api\Resource;
use Kanvas\Sdk\Util\Util;
use Kanvas\Sdk\Roles;
use Kanvas\Sdk\Apps;
use Kanvas\Sdk\Traits\PermissionsTrait;
use Kanvas\Sdk\Companies;
use Kanvas\Sdk\UserWebhooks;
use Kanvas\Sdk\FileSystemEntities;
use Kanvas\Sdk\SystemModules;
use Kanvas\Sdk\UserRoles;
use Kanvas\Sdk\Subscription;
use Kanvas\Sdk\UsersAssociatedApps;
use Kanvas\Sdk\UserLinkedSources;
use Kanvas\Sdk\UserConfig;
use Kanvas\Sdk\Sessions;


class Users extends Resource
{
    const OBJECT_NAME = 'users';
    const CANVAS_PATH = 'Canvas\Models\Users';

    use PermissionsTrait;
    use All;
    use Create;
    use Delete;
    use Update;
    use Retrieve;

    /**
     * Overwrite the user create function to return a usr object like we expect.
     *
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return object stdClass
     */
    public static function create($params = null, $opts = null): object
    {
        self::_validateParams($params);
        $url = static::classUrl();
        list($response, $opts) = static::_staticRequest('post', $url, $params, $opts);

        $user = $response->data['user'];
        $user['session'] = $response->data['session'];
        return Util::convertToSimpleObject($user, $opts, self::OBJECT_NAME);
    }

    /**
     * Get the default company of the the current user.
     *
     * @return Users
     */
    public static function getSelf(): self
    {
        return self::retrieve('0');
    }

    /**
     * Get the current User's sources
     *
     * @return KanvasObject
     */
    public function getSession(): KanvasObject
    {
        $user = self::getSelf();
        return current(Sessions::all([], ['conditions' => ["users_id:{$user->id}"]]));
    }

    /**
     * Get the current User's sources
     *
     * @return array
     */
    public function getSessions(): array
    {
        $user = self::getSelf();
        return Sessions::all([], ['conditions' => ["users_id:{$user->id}"]]);
    }

    /**
     * Get the current User's sources
     *
     * @return array
     */
    public function getConfig(): array
    {
        $user = self::getSelf();
        return UserConfig::all([], ['conditions' => ["users_id:{$user->id}"]]);
    }

    /**
     * Get the current User's sources
     *
     * @return array
     */
    public function getSources(): array
    {
        $user = self::getSelf();
        return UserLinkedSources::all([], ['conditions' => ["users_id:{$user->id}"]]);
    }

    /**
     * Get the current Users Session.
     *
     * @return KanvasObject
     */
    public function getDefaultCompany(): KanvasObject
    {
        $user = self::getSelf();
        return Companies::retrieve((string) $user->default_company);
    }

    /**
     * Get the current company of the the current user.
     *
     * @return KanvasObject
     */
    public function getCurrentCompany(): KanvasObject
    {
        return $this->getDefaultCompany();
    }

    /**
     * Get all the user's subscriptions.
     *
     * @return array
     */
    public function getAllSubscriptions(): array
    {
        $user = self::getSelf();
        $appsId = Apps::getIdByKey(getenv('GEWAER_APP_ID'));
        return Subscription::all([], [
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
    public function getCompanies(): array
    {
        $user = self::getSelf();
        $appsId = Apps::getIdByKey(getenv('GEWAER_APP_ID'));
        return UsersAssociatedApps::all([], [
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
    public function getApps(): array
    {
        $user = self::getSelf();
        return UsersAssociatedApps::all([], ['conditions' => ["users_id:{$user->id}"]]);
    }

    /**
     * Get User Webhooks.
     * @return array
     */
    public function getUserWebhook(): array
    {
        $user = self::getSelf();
        return UserWebhooks::all([], ['conditions' => ["users_id:{$user->id}"]]);
    }

    /**
     * Get User Files.
     * @return KanvasObject
     */
    public function getFiles(): KanvasObject
    {
        $user = self::getSelf();
        $appsId = Apps::getIdByKey(getenv('GEWAER_APP_ID'));
        $systemModule = SystemModules::getSystemModuleByModelName(self::CANVAS_PATH, (int)$appsId);
        return current(FileSystemEntities::all([], ['conditions' => ["entity_id:{$user->id}", "system_modules_id:{$systemModule->id}"]]));
    }

    /**
     * Get User Photo.
     * @return KanvasObject
     */
    public function getPhoto(): KanvasObject
    {
        $user = self::getSelf();
        $appsId = Apps::getIdByKey(getenv('GEWAER_APP_ID'));
        $systemModule = SystemModules::getSystemModuleByModelName(self::CANVAS_PATH, (int)$appsId);
        return current(FileSystemEntities::all([], ['conditions' => ["entity_id:{$user->id}", "system_modules_id:{$systemModule->id}"]]));
    }

    /**
     * Get user's roles
     * @return array
     */
    public function getRoles(): array
    {
        $rolesArray = [];
        $user = self::getSelf();
        // Get all user roles
        $userRoles = UserRoles::all([], ['conditions' => ["users_id:{$user->id}"]]);
        // Get all the roles by id and push them to an array
        foreach ($userRoles as $userRole) {
            $rolesArray[] = current(Roles::all([], ['conditions' => ["id:{$userRole->roles_id}"]]));
        }

        return $rolesArray;
    }

    /**
     * Get user role.
     *
     * @return KanvasObject
     */
    public function getUserRole(): KanvasObject
    {
        $user = self::getSelf();
        $appsId = Apps::getIdByKey(getenv('GEWAER_APP_ID'));
        $userRole = current(UserRoles::all([], ['conditions' => ["users_id:{$user->id}", "apps_id:{$appsId}", 'companies_id:' . $this->getCurrentCompany()->id]]));

        if ($userRole  instanceof KanvasObject) {
            return $userRole;
        }

        return current(UserRoles::all([], ['conditions' => ["users_id:{$user->id}", 'apps_id:' . Roles::DEFAULT_ACL_APP_ID, 'companies_id:' . $this->getCurrentCompany()->id]]));
    }

    /**
     * Get user role.
     *
     * @return array
     */
    public function getPermissions(): array
    {
        $user = self::getSelf();
        $appsId = Apps::getIdByKey(getenv('GEWAER_APP_ID'));
        return UserRoles::all([], ['conditions' => ["users_id:{$user->id}", "apps_id:{$appsId}", 'companies_id:' . $this->getCurrentCompany()->id]]);
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
    private function createSettingsModel(): void
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
