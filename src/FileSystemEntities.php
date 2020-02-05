<?php

declare(strict_types=1);

namespace Kanvas\Sdk;

use Kanvas\Sdk\Api\Operations\All;
use Kanvas\Sdk\Api\Operations\Create;
use Kanvas\Sdk\Api\Operations\Delete;
use Kanvas\Sdk\Api\Operations\Update;
use Kanvas\Sdk\Api\Operations\Retrieve;
use Kanvas\Sdk\Api\Resource;
use Kanvas\Sdk\Filesystem;
use Kanvas\Sdk\Util\Util;

/**
 * Filesystem Resource
 */
class FileSystemEntities extends Resource
{
    const OBJECT_NAME = 'filesystem-entity';

    use All;
    use Create;
    use Delete;
    use Update;
    use Retrieve;

    /**
     * Get a filesystem entities from this system modules.
     *
     * @param integer $id
     * @param SystemModules $systemModules
     * @param bool $isDeleted
     * @return FileSystemEntities
     */
    public static function getByIdWithSystemModule(int $id, int $systemModulesId)
    {
        // $app = Di::getDefault()->getApp();
        // $companyId = Di::getDefault()->getUserData()->currentCompanyId();

        $app = 1;
        $companyId = 2;

        //Search system modules
        // $filesystem = current(Filesystem::all([], ['conditions'=> ["apps_id:{$app->id}","is_deleted:0"]]));
        $filesystem = current(Filesystem::all([], ['conditions'=> ["apps_id:{$app}","is_deleted:0"]]));

        //Search filesystem entities
        return current(self::all([],["conditions"=>["id:{$id}","system_modules_id:{$systemModulesId}","companies_id:{$companyId}","filesystem_id:{$filesystem}","is_deleted:0"]]));
    }
}
