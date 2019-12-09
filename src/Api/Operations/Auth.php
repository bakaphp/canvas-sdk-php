<?php

namespace Kanvas\Sdk\Api\Operations;

use Kanvas\Sdk\Kanvas;
use Kanvas\Sdk\Util\Util;

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

        Kanvas::setAuthToken($response->data['token']);
        return Util::convertToSimpleObject($response->data, $opts, self::OBJECT_NAME);
    }
}
