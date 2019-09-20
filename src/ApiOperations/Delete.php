<?php
namespace Canvas\ApiOperations;
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
        list($response, $opts) = static::_staticRequest('delete', $url, $params, $options);
        return $response->json;
        // $obj = \Stripe\Util\Util::convertToStripeObject($response->json, $opts);
        // $obj->setLastResponse($response);
        // return $obj;
    }
}
