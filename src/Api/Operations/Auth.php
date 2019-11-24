<?php

namespace Canvas\Api\Operations;

use Canvas\Canvas;
use Canvas\Util\Util;

/**
 * Trait for authentication resource.
 */
trait Auth
{
    /**
     * Auth and Login
     *
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return object stdClass
     */
    public static function auth($params = null, $options = null): object
    {
        self::_validateParams($params);
        $url = static::classUrl();
        list($response, $opts) = static::_staticRequest('post', $url, $params, $options);

        Canvas::setAuthToken($response->data['token']);
        return Util::convertToSimpleObject($response->data, $opts, self::OBJECT_NAME);
    }
}
