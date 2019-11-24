<?php

namespace Canvas\Api\Operations;

use Canvas\Util\Util;
/**
 * Trait for updatable resources.
 */
trait Update
{
    /**
     * @param string $id
     * @param array|null $params
     * @param array|string|null $opts
     *
     * @return object stdClass
     */
    public function update($id, $params = null, $opts = null)
    {
        self::_validateParams($params);
        $url = self::instanceUrl($id);
        list($response, $opts) = static::_staticRequest('put', $url, $params, $opts);
        $obj = Util::convertToSimpleObject($response->data);
        return $obj;
    }
}
