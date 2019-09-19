<?php
namespace Canvas\ApiOperations;
/**
 * Trait for listable resources. Adds a `all()` static method to the class.
 *
 * This trait should only be applied to classes that derive from StripeObject.
 */
trait All
{
    /**
     * @param array|null $params
     * @param array|string|null $opts
     *
     * @throws \Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \Stripe\Collection of ApiResources
     */
    public static function all($params = null, $opts = null)
    {
        self::_validateParams($params);
        $url = static::classUrl();
        list($response, $opts) = static::_staticRequest('get', $url, $params, $opts);
        $obj = \Canvas\Util\Util::convertToStripeObject($response->json, $opts);

        /**
         * @todo Need to set Canvas Collection
         */
        if (!($obj instanceof \Canvas\Collection)) {
            throw new \Stripe\Exception\UnexpectedValueException(
                'Expected type ' . \Stripe\Collection::class . ', got "' . get_class($obj) . '" instead.'
            );
        }
        $obj->setLastResponse($response);
        $obj->setFilters($params);
        return $obj;
    }
}
