<?php

namespace Kanvas\Sdk\Api\Operations;

use Kanvas\Sdk\Util\Util;
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

        return Util::convertToSimpleObject($response->data, $opts, self::OBJECT_NAME);
    }
}
