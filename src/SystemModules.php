<?php

declare(strict_types=1);

namespace Kanvas\Sdk;

use Kanvas\Sdk\Api\Operations\All;
use Kanvas\Sdk\Api\Operations\Create;
use Kanvas\Sdk\Api\Operations\Delete;
use Kanvas\Sdk\Api\Operations\Retrieve;
use Kanvas\Sdk\Api\Operations\Update;
use Kanvas\Sdk\Api\Resource;

/**
 * System Modules Resource.
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
     * Get System Module by its model name.
     *
     * @param string $modelName
     * @param int $appsId
     *
     * @return object
     */
    public static function getSystemModuleByModelName(string $modelName, int $appsId) : object
    {
        return current(self::find([], ['conditions' => ["model_name:{$modelName}", "apps_id:{$appsId}"]]));
    }

    /**
     * Check if System Module exists otherwise create a new one.
     *
     * @param string $modelName
     * @param int $appsId
     *
     * @return object
     */
    public static function validateOrCreate(string $modelName, int $appsId) : object
    {
        $systemModule = current(self::find([], ['conditions' => ["model_name:{$modelName}", "apps_id:{$appsId}"]]));

        $className = substr($modelName, strripos($modelName, '\\') + 1);

        $classSlug = preg_replace('/(?<!^)([A-Z])/', '-\\1', $className) ? strtolower(preg_replace('/(?<!^)([A-Z])/', '-\\1', $className)) : strtolower($className);

        if (!($systemModule instanceof KanvasObject)) {
            return self::create([
                'name' => $className,
                'slug' => $classSlug,
                'model_name' => $modelName,
                'browse_fields' => '[]',
                'show' => 0,
                'protected' => 0,
                'created_at' => date('Y-m-d H:m:s'),
                'is_deleted' => 0
            ]);
        }

        return $systemModule;
    }
}
