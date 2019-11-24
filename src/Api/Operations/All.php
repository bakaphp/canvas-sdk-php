<?php

namespace Canvas\Api\Operations;

use Canvas\Util\Util;

/**
 * Trait for listable resources.
 */
trait All
{
    /**
     * Get all the records of a resource.
     *
     * @param array|null $params
     * @param array|string|null $opts
     *
     * @return object
     */
    public static function all($params = null, $opts = null): object
    {
        self::_validateParams($params);
        $url = static::classUrl();

        list($response, $opts) = static::_staticRequest('get', $url, $params, $opts);
        $obj = Util::convertToSimpleObject($response->data);
        return $obj;
    }
}
