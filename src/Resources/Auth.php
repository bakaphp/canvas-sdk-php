<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Resources;

use Kanvas\Sdk\HttpClient\CurlClient;
use Kanvas\Sdk\Resources;

class Auth extends Resources
{
    const RESOURCE_ENDPOINT = '/auth';

    /**
     * List Teams.
     *
     * Get a list of all the current user teams. You can use the query params to
     * filter your results. On admin mode, this endpoint will return a list of all
     * of the project teams. [Learn more about different API modes](/docs/admin).
     *
     * @param array $requestOptions
     *
     * @throws Exception
     *
     * @return array
     */
    public static function login(array $requestOptions = []) : array
    {
        $params = $requestOptions;

        $response = self::getClient()->call(CurlClient::METHOD_POST, self::RESOURCE_ENDPOINT, [], $params);

        return $response;
    }

    /**
     * Sets the API key to be used for requests.
     *
     * @param string $apiKey
     *
     * @return void
     */
    public static function setApiKey(string $apiKey) : void
    {
        self::getClient()->setApiKey($apiKey);
    }

    /**
     * Sets the client_id to be used for Connect requests.
     *
     * @param string $clientId
     *
     * @return void
     */
    public static function setClientId(string $clientId) : void
    {
        self::getClient()->setClientId($clientId);
    }

    /**
     * Sets the client_secret_id to be used for Connect requests.
     *
     * @param string $clientSecretId
     *
     * @return void
     */
    public static function setClienSecrettId(string $clientSecretId) : void
    {
        self::getClient()->setClienSecrettId($clientSecretId);
    }

    /**
     * Sets Authentication Token.
     *
     * @param string $authToken
     *
     * @return void
     */
    public static function setAuthToken(string $authToken) : void
    {
        self::getClient()->setAuthToken($authToken);
    }
}
