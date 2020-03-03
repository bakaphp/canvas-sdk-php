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
use Kanvas\Sdk\Roles;
use Kanvas\Sdk\Apps;
use Kanvas\Sdk\Traits\PermissionsTrait;
use Kanvas\Sdk\Companies;
use Kanvas\Sdk\UserWebhooks;
use Kanvas\Sdk\FileSystemEntities;
use Kanvas\Sdk\SystemModules;
use Kanvas\Sdk\UserRoles;

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
     * Get the default company of the the current user
     *
     * @return Users
     */
    public static function getSelf(): self
    {
        return self::retrieve('0');
    }

    /**
     * Get the current Users Session.
     *
     * @return KanvasObject
     */
    public function getDefaultCompany(): KanvasObject
    {
        $user = self::getSelf();
        return current(Companies::all([], ['conditions' => ["users_id:{$user->id}"]]));
    }

    /**
     * Get the current company of the the current user
     *
     * @return KanvasObject
     */
    public function getCurrentCompany(): KanvasObject
    {
        $user = self::getSelf();
        return current(Companies::all([], ['conditions' => ["users_id:{$user->id}"]]));
    }

    /**
     * Get User Webhooks
     * @return array
     */
    public function getUserWebhook(): array
    {
        $user = self::getSelf();
        return UserWebhooks::all([], ['conditions' => ["users_id:{$user->id}"]]);
    }

    /**
     * Get User Files
     * @return KanvasObject
     */
    public function getFiles(): KanvasObject
    {
        $user = self::getSelf();
        $appsId = Apps::getIdByKey(getenv('GEWAER_APP_ID'));
        $systemModule = SystemModules::getSystemModuleByModelName(self::CANVAS_PATH, (int)$appsId);
        return current(FileSystemEntities::all([], ['conditions' => ["entity_id:{$user->id}","system_modules_id:{$systemModule->id}"]]));
    }

    /**
     * Get User Photo
     * @return KanvasObject
     */
    public function getPhoto(): KanvasObject
    {
        $user = self::getSelf();
        $appsId = Apps::getIdByKey(getenv('GEWAER_APP_ID'));
        $systemModule = SystemModules::getSystemModuleByModelName(self::CANVAS_PATH, (int)$appsId);
        return current(FileSystemEntities::all([], ['conditions' => ["entity_id:{$user->id}","system_modules_id:{$systemModule->id}"]]));
    }

    /**
     * Get user role
     *
     * @return KanvasObject
     */
    public function getUserRole(): KanvasObject
    {
        $user = self::getSelf();
        $appsId = Apps::getIdByKey(getenv('GEWAER_APP_ID'));
        $userRole = current(UserRoles::all([], ['conditions' => ["users_id:{$user->id}","apps_id:{$appsId}","companies_id:" . $this->getCurrentCompany()->id]]));

        if ($userRole  instanceof KanvasObject) {
            return $userRole;
        }

        return current(UserRoles::all([], ['conditions' => ["users_id:{$user->id}","apps_id:" . Roles::DEFAULT_ACL_APP_ID,"companies_id:" . $this->getCurrentCompany()->id]]));
    }

    /**
     * Get user role
     *
     * @return array
     */
    public function getPermissions(): array
    {
        $user = self::getSelf();
        $appsId = Apps::getIdByKey(getenv('GEWAER_APP_ID'));
        return UserRoles::all([], ['conditions' => ["users_id:{$user->id}","apps_id:{$appsId}","companies_id:" . $this->getCurrentCompany()->id]]);
    }
}
