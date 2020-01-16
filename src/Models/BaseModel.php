<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Models;

use Kanvas\Sdk\Util\Util;
use Kanvas\Sdk\Api\Resource;

/**
 * Abstract Class Base Model.
 */
abstract class BaseModel
{
    /**
     * Resource.
     *
     * @var object
     */
    public static $resource;

    /**
     * Set Resource Variable.
     *
     * @return void
     */
    protected static function setResource($resource)
    {
        self::$resource = $resource;
    }

    /**
     * Overwrite the user create function to return a usr object like we expect.
     *
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return object stdClass
     */
    public static function find($params = null, $opts = null)
    {
        return self::$resource::all([], is_null($params) ? [] : Util::convertParams($params));
    }

    /**
     * Overwrite the user create function to return a usr object like we expect.
     *
     * @param array|string|null $params
     * @param array|string|null $options
     *
     * @return object stdClass
     */
    public static function findFirst($params = null, $opts = null)
    {
        if (!is_array($params)) {
            return self::$resource::retrieve(strval($params), [], []);
        }
        return current(self::$resource::all([], Util::convertParams($params)));
    }
}
