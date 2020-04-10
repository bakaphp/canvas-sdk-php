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

/**
 * System Modules Resource
 */
class SystemModules extends Resource
{
    const OBJECT_NAME = 'system-modules';

    use All;
    use Create;
    use Delete;
    use Update;
    use Retrieve;

    /**
     * Get System Module by its model name
     *
     * @param string $modelName
     * @param int $appsId
     * @return object
     */
    public static function getSystemModuleByModelName(string $modelName, int $appsId): object
    {
        return current(self::find(['conditions' => ["model_name:{$modelName}", "apps_id:{$appsId}"]]));
    }
}
