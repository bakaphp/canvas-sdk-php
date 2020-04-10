<?php

namespace Kanvas\Sdk\Api\Operations;

use Kanvas\Sdk\Util\Util;

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
    public static function all($params = null, $opts = null): array
    {
        self::_validateParams($params);
        $url = static::classUrl();

        list($response, $opts) = static::_staticRequest('get', $url, $params, $opts);

        return Util::convertToSimpleObject($response->data, $opts, self::OBJECT_NAME);
    }

    /**
     * Get all the records of a resource.
     *
     * @param array|null $params
     * @param array|string|null $opts
     *
     * @return object
     */
    public static function find($queryParams = null, $requestParams = []): array
    {
        self::_validateParams($requestParams);
        $url = static::classUrl();

        list($response, $queryParams) = static::_staticRequest('get', $url, $requestParams, $queryParams);

        return Util::convertToSimpleObject($response->data, $queryParams, self::OBJECT_NAME);
    }
}
