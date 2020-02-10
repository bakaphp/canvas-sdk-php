<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Traits;

use Exception;
use Kanvas\Sdk\Roles;
use Kanvas\Sdk\Apps;

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
     * @return UserRoles
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
                'ACL - User doesnt have any set roles in this current app ' . $this->di->getApp()->name
            );
        }

        return $this->di->getAcl()->isAllowed($role->name, $resource, $action);
    }
}
