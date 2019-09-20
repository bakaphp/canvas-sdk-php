<?php

namespace Canvas\ApiOperations;

use Canvas\Canvas;

/**
 * Trait for creatable resources. Adds a `create()` static method to the class.
 *
 * This trait should only be applied to classes that derive from StripeObject.
 */
trait Auth
{
    /**
     * @param array|null $params
     * @param array|string|null $options
     *
     * @throws \Stripe\Exception\ApiErrorException if the request fails
     *
     * @return static The created resource.
     */
    public static function auth($params = null, $options = null)
    {
        self::_validateParams($params);
        $url = static::classUrl();
        list($response, $opts) = static::_staticRequest('post', $url, $params, $options);
        Canvas::setAuthToken($response->json['token']);
        return Canvas::getAuthToken();
        // $obj = \Stripe\Util\Util::convertToStripeObject($response->json, $opts);
        // $obj->setLastResponse($response);
        // return $obj;
    }
}
