<?php

namespace Kanvas\Sdk\Api;

use Kanvas\Sdk\Exception;
use Kanvas\Sdk\Kanvas;
use Kanvas\Sdk\HttpClient\GuzzleClient;

/**
 * Class Requestor.
 *
 * @package Canvas
 */
class Requestor
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
     * Requestor constructor.
     *
     * @param string|null $apiKey
     * @param string|null $apiBase
     */
    public function __construct($apiKey = null, $apiBase = null)
    {
        $this->apiKey = $apiKey;
        if (!$apiBase) {
            $apiBase = Kanvas::$apiBase;
        }
        $this->apiBase = $apiBase;
    }

    /**
     * @static
     *
     * @param Resource|bool|array|mixed $d
     *
     * @return Resource|array|string|mixed
     */
    private static function _encodeObjects($decoded)
    {
        if ($decoded instanceof Resource) {
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
     */
    public function request($method, $url, $params = null, $headers = null, $query = '')
    {
        $params = $params ?: [];
        $headers = $headers ?: [];
        list($responseBody, $responseCode, $responseHeaders, $apiKeyUsed) = $this->_requestRaw($method, $url, $params, $headers, $query);
        $data = $this->_interpretResponse($responseBody, $responseCode, $responseHeaders);
        $resp = new Response($responseBody, $responseCode, $responseHeaders, $data);
        return [$resp, $apiKeyUsed];
    }

    /**
     * Handles errors in response.
     *
     * @param string $rbody A JSON string.
     * @param int $rcode
     * @param array $rheaders
     * @param array $resp
     *
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
     * Sets default headers to be used in requests.
     * @static
     *
     * @param string $apiKey
     * @param null   $clientInfo
     *
     * @return array
     */
    private static function _defaultHeaders($apiKey, $clientInfo = null): array
    {
        $defaultHeaders = [
            'Authorization' => Kanvas::getAuthToken(),
            'Key' => $apiKey,
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];

        return $defaultHeaders;
    }

    /**
     * Makes the actual request to an external API.
     *
     * @param string $method
     * @param string $url
     * @param array  $params
     * @param array  $headers
     *
     * @return array
     */
    private function _requestRaw($method, $url, $params, $headers, $query): array
    {
        $apiKey = $this->apiKey;
        if (!$apiKey) {
            $apiKey = Kanvas::$apiKey;
        }

        if (!$apiKey) {
            $msg = 'No API key provided.  (HINT: set your API key using '
              . '"Kanvas::setApiKey(<API-KEY>)".  You can generate API keys from '
              . 'the Kanvas web interface.';
            throw new Exception\Authentication($msg);
        }

        if (!Kanvas::getAuthToken() && !strpos($url, 'auth')) {
            $msg = 'No Auth Token set.  (HINT: set your Auth Token using the auth call';
            throw new Exception\Authentication($msg);
        }

        $absoluteUrl = $this->apiBase . $url . $query;

        $body = ['form_params' => $params];

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
        $rheaders = $response->getHeader('content-type');

        return [$rbody, $rcode, $rheaders, $apiKey];
    }

    /**
     * Interpret every section of a response making sure is a valid response.
     *
     * @param string $rbody
     * @param int    $rcode
     * @param array  $rheaders
     *
     * @return mixed
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
     * Set a HttpClient.
     * @static
     *
     * @param HttpClient\ClientInterface $client
     */
    public static function setHttpClient($client)
    {
        self::$httpClient = $client;
    }

    /**
     * Returns an instance of the HttpClient.
     *
     * @return HttpClient\ClientInterface
     */
    private function httpClient()
    {
        if (!self::$httpClient) {
            /**
             * @todo Replce CurlClient with new Guzzle client
             */
            self::$httpClient = GuzzleClient::instance();
        }
        return self::$httpClient;
    }
}
