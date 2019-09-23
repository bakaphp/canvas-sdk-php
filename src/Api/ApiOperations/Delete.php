<?php

namespace Canvas\Api\ApiOperations;

use Canvas\Util\Util;
/**
 * Trait for deletable resources. Adds a `delete()` method to the class.
 *
 * This trait should only be applied to classes that derive from StripeObject.
 */
trait Delete
{
    /**
     * @param array|null $params
     * @param array|string|null $opts
     *
     * @throws \Stripe\Exception\ApiErrorException if the request fails
     *
     * @return static The deleted resource.
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