<?php

namespace Canvas\Api\ApiOperations;

use Canvas\Util\Util;
/**
 * Trait for deletable resources.
 */
trait Delete
{
    /**
     * Delete a record for a resource
     *
     * @param array|null $params
     * @param array|string|null $opts
     *
     * @return object stdClass
     */
    public function delete($id, $params = null, $opts = null)
    {
        self::_validateParams($params);
        $url = self::instanceUrl($id);
        list($response, $opts) = static::_staticRequest('delete', $url, $params, $opts);
        $obj = Util::convertToSimpleObject($response->json);
        return $obj;
    }
}
