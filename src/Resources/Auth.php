<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Resources;

use Kanvas\Sdk\HttpClient\CurlClient;
use Kanvas\Sdk\Resources;

class Auth extends Resources
{
    /**
     * @param Client $client
     */
    public function __construct()
    {
        $this->client = CurlClient::getInstance();
        $this->resource = '/' . 'auth';
    }

    /**
     * List Teams.
     *
     * Get a list of all the current user teams. You can use the query params to
     * filter your results. On admin mode, this endpoint will return a list of all
     * of the project teams. [Learn more about different API modes](/docs/admin).
     *
     * @param string  $search
     * @param int  $limit
     * @param int  $offset
     * @param string  $orderType
     *
     * @throws Exception
     *
     * @return array
     */
    public function login(array $requestOptions = []) : array
    {
        $params = $requestOptions;

        $response = $this->client->call(CurlClient::METHOD_POST, $this->resource, [
            'content-type' => 'application/json',
        ], $params);

        $this->client->addHeader('Authorization', $response['token']);

        return $response;
    }
}
