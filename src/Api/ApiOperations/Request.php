<?php

namespace Canvas\Api\ApiOperations;

use Canvas\Api\ApiRequestor;
use Canvas\Util\RequestOptions;

/**
 * Trait for resources that need to make API requests.
 *
 * This trait should only be applied to classes that derive from CanvasObject.
 */
trait Request
{
    /**
     * @param array|null|mixed $params The list of parameters to validate
     *
     * @throws \Canvas\Exception\Api if $params exists and is not an array
     */
    protected static function _validateParams($params = null)
    {
        if ($params && !is_array($params)) {
            $message = 'You must pass an array as the first argument to Stripe API '
               . 'method calls.';
            throw new \Canvas\Exception\Api($message);
        }
    }

    /**
     * @param string $method HTTP method ('get', 'post', etc.)
     * @param string $url URL for the request
     * @param array $params list of parameters for the request
     * @param array|string|null $options
     *
     * @return array tuple containing (the JSON response, $options)
     */
    protected function _request($method, $url, $params = [], $options = null)
    {
        $opts = $this->_opts->merge($options);
        list($resp, $options) = static::_staticRequest($method, $url, $params, $opts);
        $this->setLastResponse($resp);
        return [$resp->json, $options];
    }

    /**
     * @param string $method HTTP method ('get', 'post', etc.)
     * @param string $url URL for the request
     * @param array $params list of parameters for the request
     * @param array|string|null $options
     *
     * @return array tuple containing (the JSON response, $options)
     */
    protected static function _staticRequest($method, $url, $params, $options)
    {
        $requestOptions = RequestOptions::parse($options);
        $baseUrl = isset($requestOptions->apiBase) ? $requestOptions->requestOptions : static::baseUrl();
        $requestor = new ApiRequestor($requestOptions->apiKey, $baseUrl);
        list($response, $requestOptions->apiKey) = $requestor->request($method, $url, $params, $requestOptions->headers);
        $requestOptions->discardNonPersistentHeaders();
        return [$response, $requestOptions];
    }
}
