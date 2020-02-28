<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Traits;

use Exception;
use Kanvas\Sdk\Roles;
use Kanvas\Sdk\Apps;
use Kanvas\Sdk\KanvasObject;
use Kanvas\Sdk\UserRoles;

/**
 * Trait FractalTrait.
 *
 * @package Canvas\Traits
 */
trait PermissionsTrait
{
    /**
     * Overwrite the permission relationship to force the user of company id.
     *
     * @return Roles
     */
    public function getPermission()
    {
        return Roles::getUserRole(Apps::getIdByKey(getenv('GEWAER_APP_ID')));
    }

    /**
     * At this current system / app can you do this?
     *
     * Example: resource.action
     *  Leads.add || leads.updates || lead.delete
     *
     * @param string $action
     * @return boolean
     */
    public function can(string $action): bool
    {
        //if we find the . then les
        if (strpos($action, '.') === false) {
            throw new Exception('ACL - We are expecting the resource for this action');
        }

        $action = explode('.', $action);
        $resource = $action[0];
        $action = $action[1];

        //get your user account role for this app or the canvas ecosystem
        if (!$role = $this->getPermission()) {
            throw new Exception(
                'ACL - User doesnt have any set roles in this current app '
            );
        }

        return $this->di->getAcl()->isAllowed($role->name, $resource, $action);
    }

    /**
     * Assigne a user this role
     * Example: App.Role.
     *
     * @param string $role
     * @return boolean
     */
    public function assignRole(string $role): bool
    {
        /**
         * check if we have a dot, that mes it legacy and sending the app name
         * not needed any more so we remove it.
         */
        if (strpos($role, '.') !== false) {
            $appRole = explode('.', $role);
            $role = $appRole[1];
        }

        $role = Roles::getByName($role, $this->default_company, getenv('GEWAER_APP_ID'));

        $userRole = current(UserRoles::all([], [
            'conditions' => [
                "users_id:{$this->id}",
                "roles_id:{$role->id}",
                "apps_id:{$role->apps_id}",
                "companies_id:{$this->default_company}",
            ]]));

        if (!is_object($userRole)) {
            $userRole = UserRoles::create([
                'users_id' => $this->id,
                'roles_id' => $role->id,
                'apps_id' => $role->apps_id,
                'companies_id' => $this->default_company
            ]);
        }

        return true;
    }

    /**
     * Remove a role for the current user
     * Example: App.Role.
     *
     * @param string $role
     * @return boolean
     */
    public function removeRole(string $role): bool
    {
        $role = Roles::getByAppName($role, $this->default_company, getenv('GEWAER_APP_ID'));

        if (!is_object($role)) {
            throw new Exception('Role not found in DB');
        }

        $userRole = current(UserRoles::all([], [
            'conditions' => [
                "users_id:{$this->id}",
                "roles_id:{$role->id}",
                "apps_id:{$role->apps_id}",
                "companies_id:{$this->default_company}",
            ]]));

        if (!$userRole instanceof KanvasObject) {
            $userRole = current(UserRoles::all([], [
                'conditions' => [
                    "users_id:{$this->id}",
                    "roles_id:{$role->id}",
                    "apps_id:{$this->di->getApp()->getId()}",
                    "companies_id:{$this->default_company}",
                ]]));
        }

        if (is_object($userRole)) {
            return  UserRoles::delete($userRole->id, [], []);
        }

        return false;
    }

    /**
     * Check if the user has this role.
     *
     * @param string $role
     * @return boolean
     */
    public function hasRole(string $role): bool
    {
        $role = Roles::getByAppName($role, $this->default_company, getenv('GEWAER_APP_ID'));

        if (!is_object($role)) {
            throw new Exception('Role not found in DB');
        }

        $userRole = current(UserRoles::all([], [
            'conditions' => [
                "users_id:{$this->id}",
                "roles_id:{$role->id}",
                "apps_id:{$role->apps_id}",
                "companies_id:{$this->default_company}",
            ]]));

        if (!$userRole instanceof KanvasObject) {
            $userRole = current(UserRoles::all([], [
                'conditions' => [
                    "users_id:{$this->id}",
                    "roles_id:{$role->id}",
                    "apps_id:{$this->di->getApp()->getId()}",
                    "companies_id:{$this->default_company}",
                ]]));
        }

        if (is_object($userRole)) {
            return true;
        }

        return false;
    }
}
