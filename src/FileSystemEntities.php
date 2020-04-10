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
use Kanvas\Sdk\KanvasObject;
use Kanvas\Sdk\Apps;

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
    public static function getByIdWithSystemModule(int $id, int $systemModulesId, int $appId, int $currentCompanyId)
    {
        $filesystem = Filesystem::find(['conditions'=> ["apps_id:{$appId}","is_deleted:0"]]);

        foreach ($filesystem as $file) {
            $filesystemEntity = current(self::find(["conditions"=>["id:{$id}","system_modules_id:{$systemModulesId}","companies_id:{$currentCompanyId}","filesystem_id:{$file->id}","is_deleted:0"]]));
            if ($filesystemEntity instanceof KanvasObject) {
                return $filesystemEntity;
            }
        }
    }

    /**
     * Get all filesystem entities by entity_id
     *
     * @return array
     */
    public static function getAllByEntityId(int $id, int $appId, int $currentCompanyId)
    {
        $entitiesArray = [];
        $filesystem = Filesystem::find(['conditions'=> ["apps_id:{$appId}","is_deleted:0"]]);

        foreach ($filesystem as $file) {
            $filesystemEntity = current(self::find(["conditions"=>["entity_id:{$id}","companies_id:{$currentCompanyId}","filesystem_id:{$file->id}","is_deleted:0"]]));
            if ($filesystemEntity instanceof KanvasObject) {
                $entitiesArray[] = $filesystemEntity;
            }
        }

        return $entitiesArray;
    }
}
