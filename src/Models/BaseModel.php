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
     * Set Resource Variable.
     *
     * @return void
     */
    protected static function getSource(): string
    {
        return Resource::class;
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
        return static::getSource()::find(is_null($params) ? [] : Util::convertParams($params), []);
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
            return static::getSource()::retrieve(strval($params), [], []);
        }
        return current(static::getSource()::find(Util::convertParams($params)), []);
    }
}
