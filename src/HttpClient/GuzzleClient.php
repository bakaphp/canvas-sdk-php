<?php

namespace Kanvas\Sdk\HttpClient;

use GuzzleHttp\Client;

class GuzzleClient implements ClientInterface
{
    private static $instance;

    /**
     * Singleton
     *
     * @return void
     */
    public static function instance(): ClientInterface
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param string $method The HTTP method being used
     * @param string $absoluteUrl The URL being requested, including domain and protocol
     * @param array $headers Headers to be used in the request (full strings, not Key-Value pairs)
     * @param array $params Key-Value pairs for parameters. Can be nested for arrays and hashes
     * @param boolean $hasFile Whether or not $params references a file (via an @ prefix or
     *                         CurlFile)
     *
     * @throws \Kanvas\Sdk\Exception\Api
     * @throws \Kanvas\Sdk\Exception\ApiConnection
     * @return array An array whose first element is raw request body, second
     *    element is HTTP status code and third array of HTTP headers.
     */
    public function request($method, $absoluteUrl, $headers, $params, $hasFile)
    {
        $client = new Client(['headers' => $headers]);
        $res = $client->request(strtoupper($method), $absoluteUrl, $params);

        return $res;
    }
}
