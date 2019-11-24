<?php

namespace Canvas\Api\Operations;

use Canvas\Util\Util;
/**
 * Trait for listing an specific record of the resources.
 */
trait Retrieve
{
    /**
     * Retrieve a record of a resource by its id
     *
     * @param string $id
     * @param array|null $params
     * @param array|string|null $opts
     *
     * @return object stdClass
     */
    public static function retrieve( string $id, $params = null, $opts = null): object
    {
        self::_validateParams($params);
        $url = self::instanceUrl($id);
        list($response, $opts) = static::_staticRequest('get', $url, $params, $opts);
        $obj = Util::convertToSimpleObject($response->data, $opts);
        return $obj;
    }

    /**
     * Get the element by its id
     *
     * @param string $id
     * @return object
     */
    public function getById(string $id): object
    {
        return self::retrieve($id);
    }
}
