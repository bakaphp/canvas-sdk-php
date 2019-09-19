<?php

namespace Canvas;

use Canvas\Exception;

/**
 * Class ApiRequestor
 *
 * @package Canvas
 */
class ApiRequestor
{
    /**
     * @var string|null
     */
    private $apiKey;

    /**
     * @var string
     */
    private $apiBase;

    /**
     * @var HttpClient\ClientInterface
     */
    private static $httpClient;

    /**
     * ApiRequestor constructor.
     *
     * @param string|null $apiKey
     * @param string|null $apiBase
     */
    public function __construct($apiKey = null, $apiBase = null)
    {
        $this->apiKey = $apiKey;
        if (!$apiBase) {
            $apiBase = Canvas::$apiBase;
        }
        $this->apiBase = $apiBase;
    }

    /**
     * @static
     *
     * @param ApiResource|bool|array|mixed $d
     *
     * @return ApiResource|array|string|mixed
     */
    private static function _encodeObjects($decoded)
    {
        if ($decoded instanceof ApiResource) {
            return Util\Util::utf8($decoded->id);
        } elseif ($decoded === true) {
            return 'true';
        } elseif ($decoded === false) {
            return 'false';
        } elseif (is_array($decoded)) {
            $res = [];
            foreach ($decoded as $k => $v) {
                $res[$k] = self::_encodeObjects($v);
            }
            return $res;
        } else {
            return Util\Util::utf8($decoded);
        }
    }

    /**
     * @param string     $method
     * @param string     $url
     * @param array|null $params
     * @param array|null $headers
     *
     * @return array An array whose first element is an API response and second
     *    element is the API key used to make the request.
     * @throws Exception\Api
     * @throws Exception\Authentication
     * @throws Exception\InvalidRequest
     * @throws Exception\Permission
     * @throws Exception\ApiConnection
     */
    public function request($method, $url, $params = null, $headers = null)
    {
        $params = $params ?: [];
        $headers = $headers ?: [];
        list($responseBody, $responseCode, $responseHeaders, $apiKeyUsed) =
        $this->_requestRaw($method, $url, $params, $headers);
        $json = $this->_interpretResponse($responseBody, $responseCode, $responseHeaders);
        $resp = new ApiResponse($responseBody, $responseCode, $responseHeaders, $json);
        return [$resp, $apiKeyUsed];
    }

    /**
     * @param string $rbody A JSON string.
     * @param int $rcode
     * @param array $rheaders
     * @param array $resp
     *
     * @throws Exception\InvalidRequest if the error is caused by the user.
     * @throws Exception\Authentication if the error is caused by a lack of
     *    permissions.
     * @throws Exception\Permission if the error is caused by insufficient
     *    permissions.
     * @throws Exception\Card if the error is the error code is 402 (payment
     *    required)
     * @throws Exception\InvalidRequest if the error is caused by the user.
     * @throws Exception\Idempotency if the error is caused by an idempotency key.
     * @throws Exception\OAuth\InvalidClient
     * @throws Exception\OAuth\InvalidGrant
     * @throws Exception\OAuth\InvalidRequest
     * @throws Exception\OAuth\InvalidScope
     * @throws Exception\OAuth\UnsupportedGrantType
     * @throws Exception\OAuth\UnsupportedResponseType
     * @throws Exception\Permission if the error is caused by insufficient
     *    permissions.
     * @throws Exception\RateLimit if the error is caused by too many requests
     *    hitting the API.
     * @throws Exception\Api otherwise.
     */
    public function handleErrorResponse($rbody, $rcode, $rheaders, $resp)
    {
        if (!is_array($resp) || !isset($resp['error'])) {
            $msg = "Invalid response object from API: $rbody "
              . "(HTTP response code was $rcode)";
            throw new Exception\Api($msg, $rcode, $rbody, $resp, $rheaders);
        }

        $errorData = $resp['error'];

        $error = null;
        if (is_string($errorData)) {
            $error = self::_specificOAuthError($rbody, $rcode, $rheaders, $resp, $errorData);
        }
        if (!$error) {
            $error = self::_specificAPIError($rbody, $rcode, $rheaders, $resp, $errorData);
        }

        throw $error;
    }

    /**
     * @static
     *
     * @param string $rbody
     * @param int    $rcode
     * @param array  $rheaders
     * @param array  $resp
     * @param array  $errorData
     *
     * @return Exception\RateLimit|Exception\Idempotency|Exception\InvalidRequest|Exception\Authentication|Exception\Card|Exception\Permission|Exception\Api
     */
    private static function _specificAPIError($rbody, $rcode, $rheaders, $resp, $errorData)
    {
        $msg = isset($errorData['message']) ? $errorData['message'] : null;
        $param = isset($errorData['param']) ? $errorData['param'] : null;
        $code = isset($errorData['code']) ? $errorData['code'] : null;
        $type = isset($errorData['type']) ? $errorData['type'] : null;

        switch ($rcode) {
            case 400:
                // 'rate_limit' code is deprecated, but left here for backwards compatibility
                // for API versions earlier than 2015-09-08
                if ($code == 'rate_limit') {
                    return new Exception\RateLimit($msg, $param, $rcode, $rbody, $resp, $rheaders);
                }
                if ($type == 'idempotency_error') {
                    return new Exception\Idempotency($msg, $rcode, $rbody, $resp, $rheaders);
                }

                // intentional fall-through
            case 404:
                return new Exception\InvalidRequest($msg, $param, $rcode, $rbody, $resp, $rheaders);
            case 401:
                return new Exception\Authentication($msg, $rcode, $rbody, $resp, $rheaders);
            case 402:
                return new Exception\Card($msg, $param, $code, $rcode, $rbody, $resp, $rheaders);
            case 403:
                return new Exception\Permission($msg, $rcode, $rbody, $resp, $rheaders);
            case 429:
                return new Exception\RateLimit($msg, $param, $rcode, $rbody, $resp, $rheaders);
            default:
                return new Exception\Api($msg, $rcode, $rbody, $resp, $rheaders);
        }
    }

    /**
     * @static
     *
     * @param string|bool $rbody
     * @param int         $rcode
     * @param array       $rheaders
     * @param array       $resp
     * @param string      $errorCode
     *
     * @return null|Exception\OAuth\InvalidClient|Exception\OAuth\InvalidGrant|Exception\OAuth\InvalidRequest|Exception\OAuth\InvalidScope|Exception\OAuth\UnsupportedGrantType|Exception\OAuth\UnsupportedResponseType
     */
    private static function _specificOAuthError($rbody, $rcode, $rheaders, $resp, $errorCode)
    {
        $description = isset($resp['error_description']) ? $resp['error_description'] : $errorCode;

        switch ($errorCode) {
            case 'invalid_client':
                return new Exception\OAuth\InvalidClient($errorCode, $description, $rcode, $rbody, $resp, $rheaders);
            case 'invalid_grant':
                return new Exception\OAuth\InvalidGrant($errorCode, $description, $rcode, $rbody, $resp, $rheaders);
            case 'invalid_request':
                return new Exception\OAuth\InvalidRequest($errorCode, $description, $rcode, $rbody, $resp, $rheaders);
            case 'invalid_scope':
                return new Exception\OAuth\InvalidScope($errorCode, $description, $rcode, $rbody, $resp, $rheaders);
            case 'unsupported_grant_type':
                return new Exception\OAuth\UnsupportedGrantType($errorCode, $description, $rcode, $rbody, $resp, $rheaders);
            case 'unsupported_response_type':
                return new Exception\OAuth\UnsupportedResponseType($errorCode, $description, $rcode, $rbody, $resp, $rheaders);
        }

        return null;
    }

    /**
     * @static
     *
     * @param null|array $appInfo
     *
     * @return null|string
     */
    private static function _formatAppInfo($appInfo)
    {
        if ($appInfo !== null) {
            $string = $appInfo['name'];
            if ($appInfo['version'] !== null) {
                $string .= '/' . $appInfo['version'];
            }
            if ($appInfo['url'] !== null) {
                $string .= ' (' . $appInfo['url'] . ')';
            }
            return $string;
        } else {
            return null;
        }
    }

    /**
     * @static
     *
     * @param string $apiKey
     * @param null   $clientInfo
     *
     * @return array
     */
    private static function _defaultHeaders($apiKey, $clientInfo = null)
    {
        $uaString = 'Stripe/v1 PhpBindings/' . Stripe::VERSION;

        $langVersion = phpversion();
        $uname = php_uname();

        $appInfo = Stripe::getAppInfo();
        $ua = [
            'bindings_version' => Stripe::VERSION,
            'lang' => 'php',
            'lang_version' => $langVersion,
            'publisher' => 'stripe',
            'uname' => $uname,
        ];
        if ($clientInfo) {
            $ua = array_merge($clientInfo, $ua);
        }
        if ($appInfo !== null) {
            $uaString .= ' ' . self::_formatAppInfo($appInfo);
            $ua['application'] = $appInfo;
        }

        $defaultHeaders = [
            'X-Stripe-Client-User-Agent' => json_encode($ua),
            'User-Agent' => $uaString,
            'Authorization' => 'Bearer ' . $apiKey,
        ];
        return $defaultHeaders;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array  $params
     * @param array  $headers
     *
     * @return array
     * @throws Exception\Api
     * @throws Exception\ApiConnection
     * @throws Exception\Authentication
     */
    private function _requestRaw($method, $url, $params, $headers)
    {
        $apiKey = $this->apiKey;
        if (!$apiKey) {
            $apiKey = Canvas::$apiKey;
        }

        if (!$apiKey) {
            $msg = 'No API key provided.  (HINT: set your API key using '
              . '"Canvas::setApiKey(<API-KEY>)".  You can generate API keys from '
              . 'the Canvas web interface.';
            throw new Exception\Authentication($msg);
        }

        // Clients can supply arbitrary additional keys to be included in the
        // X-Stripe-Client-User-Agent header via the optional getUserAgentInfo()
        // method
        $clientUserAgentInfo = null;
        if (method_exists($this->httpClient(), 'getUserAgentInfo')) {
            $clientUserAgentInfo = $this->httpClient()->getUserAgentInfo();
        }

        $absoluteUrl = $this->apiBase.$url;

        $params = self::_encodeObjects($params);

        $defaultHeaders = $this->_defaultHeaders($apiKey, $clientUserAgentInfo);
        if (Stripe::$apiVersion) {
            $defaultHeaders['Stripe-Version'] = Stripe::$apiVersion;
        }

        if (Stripe::$accountId) {
            $defaultHeaders['Stripe-Account'] = Stripe::$accountId;
        }

        if (Stripe::$enableTelemetry && self::$requestTelemetry != null) {
            $defaultHeaders["X-Stripe-Client-Telemetry"] = self::_telemetryJson(self::$requestTelemetry);
        }

        $hasFile = false;
        $hasCurlFile = class_exists('\CURLFile', false);
        foreach ($params as $k => $v) {
            if (is_resource($v)) {
                $hasFile = true;
                $params[$k] = self::_processResourceParam($v, $hasCurlFile);
            } elseif ($hasCurlFile && $v instanceof \CURLFile) {
                $hasFile = true;
            }
        }

        if ($hasFile) {
            $defaultHeaders['Content-Type'] = 'multipart/form-data';
        } else {
            $defaultHeaders['Content-Type'] = 'application/x-www-form-urlencoded';
        }

        $combinedHeaders = array_merge($defaultHeaders, $headers);
        $rawHeaders = [];

        foreach ($combinedHeaders as $header => $value) {
            $rawHeaders[] = $header . ': ' . $value;
        }

        $requestStartMs = Util\Util::currentTimeMillis();

        list($rbody, $rcode, $rheaders) = $this->httpClient()->request(
            $method,
            $absoluteUrl,
            $rawHeaders,
            $params,
            $hasFile
        );

        if (array_key_exists('request-id', $rheaders)) {
            self::$requestTelemetry = new RequestTelemetry(
                $rheaders['request-id'],
                Util\Util::currentTimeMillis() - $requestStartMs
            );
        }

        return [$rbody, $rcode, $rheaders, $apiKey];
    }

    /**
     * @param resource $resource
     * @param bool     $hasCurlFile
     *
     * @return \CURLFile|string
     * @throws Exception\Api
     */
    private function _processResourceParam($resource, $hasCurlFile)
    {
        if (get_resource_type($resource) !== 'stream') {
            throw new Exception\Api(
                'Attempted to upload a resource that is not a stream'
            );
        }

        $metaData = stream_get_meta_data($resource);
        if ($metaData['wrapper_type'] !== 'plainfile') {
            throw new Exception\Api(
                'Only plainfile resource streams are supported'
            );
        }

        if ($hasCurlFile) {
            // We don't have the filename or mimetype, but the API doesn't care
            return new \CURLFile($metaData['uri']);
        } else {
            return '@'.$metaData['uri'];
        }
    }

    /**
     * @param string $rbody
     * @param int    $rcode
     * @param array  $rheaders
     *
     * @return mixed
     * @throws Exception\Api
     * @throws Exception\Authentication
     * @throws Exception\Card
     * @throws Exception\InvalidRequest
     * @throws Exception\OAuth\InvalidClient
     * @throws Exception\OAuth\InvalidGrant
     * @throws Exception\OAuth\InvalidRequest
     * @throws Exception\OAuth\InvalidScope
     * @throws Exception\OAuth\UnsupportedGrantType
     * @throws Exception\OAuth\UnsupportedResponseType
     * @throws Exception\Permission
     * @throws Exception\RateLimit
     * @throws Exception\Idempotency
     */
    private function _interpretResponse($rbody, $rcode, $rheaders)
    {
        $resp = json_decode($rbody, true);
        $jsonError = json_last_error();
        if ($resp === null && $jsonError !== JSON_ERROR_NONE) {
            $msg = "Invalid response body from API: $rbody "
              . "(HTTP response code was $rcode, json_last_error() was $jsonError)";
            throw new Exception\Api($msg, $rcode, $rbody);
        }

        if ($rcode < 200 || $rcode >= 300) {
            $this->handleErrorResponse($rbody, $rcode, $rheaders, $resp);
        }
        return $resp;
    }

    /**
     * @static
     *
     * @param HttpClient\ClientInterface $client
     */
    public static function setHttpClient($client)
    {
        self::$httpClient = $client;
    }

    /**
     * @static
     *
     * Resets any stateful telemetry data
     */
    public static function resetTelemetry()
    {
        self::$requestTelemetry = null;
    }

    /**
     * @return HttpClient\ClientInterface
     */
    private function httpClient()
    {
        if (!self::$httpClient) {

            /**
             * @todo Replce CurlClient with new Guzzle client
             */
            self::$httpClient = HttpClient\CurlClient::instance();
        }
        return self::$httpClient;
    }
}
