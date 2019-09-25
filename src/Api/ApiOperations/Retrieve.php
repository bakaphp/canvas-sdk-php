<?php

namespace Canvas\Api\ApiOperations;

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
    public function retrieve( string $id, $params = null, $opts = null)
    {
        self::_validateParams($params);
        $url = self::instanceUrl($id);
        list($response, $opts) = static::_staticRequest('get', $url, $params, $opts);
        $obj = Util::convertToSimpleObject($response->data);
        return $obj;
    }
}
