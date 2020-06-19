<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Resources;

use Kanvas\Sdk\Contracts\CrudOperationsTrait;
use Kanvas\Sdk\Resources;

class SystemModules extends Resources
{
    const RESOURCE_NAME = 'system-modules';

    use CrudOperationsTrait;

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
        return self::findFirst(null, ['conditions' => ["model_name:{$modelName}", "apps_id:{$appsId}"]]);
    }

    /**
     * Check if System Module exists otherwise create a new one.
     *
     * @param string $modelName
     * @param int $appsId
     *
     * @return object
     */
    public static function validateOrCreate(string $modelName, int $appsId) : array
    {
        $systemModule = self::findFirst(null, ['conditions' => ["model_name:{$modelName}", "apps_id:{$appsId}"]]);

        $className = substr($modelName, strripos($modelName, '\\') + 1);

        $classSlug = preg_replace('/(?<!^)([A-Z])/', '-\\1', $className) ? strtolower(preg_replace('/(?<!^)([A-Z])/', '-\\1', $className)) : strtolower($className);

        if (!($systemModule instanceof KanvasObject)) {
            return self::create([
                'name' => $className,
                'slug' => $classSlug,
                'model_name' => $modelName,
                'browse_fields' => '[]',
                'show' => 1,
                'protected' => 0,
                'created_at' => date('Y-m-d H:m:s'),
                'is_deleted' => 0
            ]);
        }

        return $systemModule;
    }
}
