<?php

namespace Canvas;

use Canvas\Exception;

/**
 * Class ApiRequestor.
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
        list($responseBody, $responseCode, $responseHeaders, $apiKeyUsed) = $this->_requestRaw($method, $url, $params, $headers);
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
        $defaultHeaders = [
            'Authorization' => Canvas::getAuthToken(),
            'Key'=> $apiKey,
            'Content-Type'=> 'application/x-www-form-urlencoded'
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


        if (!Canvas::getAuthToken() && !strpos($url, 'auth')) {
            $msg = 'No Auth Token set.  (HINT: set your Auth Token using the auth call';
            throw new Exception\Authentication($msg);
        }

        $absoluteUrl = $this->apiBase . $url;

        $body = ['form_params'=> $params];

        $defaultHeaders = $this->_defaultHeaders($apiKey);

        $hasFile = false;

        $response = $this->httpClient()->request(
            $method,
            $absoluteUrl,
            $defaultHeaders,
            $body,
            $hasFile
        );

        $rbody = $response->getBody();
        $rcode = $response->getStatusCode();
        $rheaders= $response->getHeader('content-type');

        return [$rbody,$rcode, $rheaders, $apiKey];
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
            return '@' . $metaData['uri'];
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
            self::$httpClient = HttpClient\GuzzleClient::instance();
        }
        return self::$httpClient;
    }
}
