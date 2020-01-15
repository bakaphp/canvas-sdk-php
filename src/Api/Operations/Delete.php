<?php

namespace Kanvas\Sdk\Api\Operations;

use Kanvas\Sdk\Util\Util;
/**
 * Trait for deletable resources.
 */
trait Delete
{
    /**
     * Delete a record for a resource
     *
     * @param string $id
     * @param array|null $params
     * @param array|string|null $opts
     *
     * @return array
     */
    public static function delete(string $id, $params = null, $opts = null): array
    {
        self::_validateParams($params);
        $url = self::instanceUrl($id);
        list($response, $opts) = static::_staticRequest('delete', $url, $params, $opts);
        return $response->data;
    }
}
