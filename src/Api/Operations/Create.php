<?php

namespace Canvas\Api\Operations;

use Canvas\Util\Util;
/**
 * Trait for creatable resources.
 */
trait Create
{
    /**
     * Create a new record for a resource
     *
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return object stdClass
     */
    public static function create($params = null, $opts = null): object
    {
        self::_validateParams($params);
        $url = static::classUrl();
        list($response, $opts) = static::_staticRequest('post', $url, $params, $opts);

        return Util::convertToSimpleObject($response->data, $opts, self::OBJECT_NAME);
    }
}
