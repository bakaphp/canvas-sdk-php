<?php

namespace Kanvas\Sdk\Contracts;

use Kanvas\Sdk\HttpClient\CurlClient;
use Kanvas\Sdk\Util\RequestOptions;
use Kanvas\Sdk\Util\Util;

/**
 * Trait CrudOperationsTrait.
 *
 * @package Canvas\Traits
 *
 * @property Users $user
 * @property Config $config
 * @property Request $request
 * @property Auth $auth
 * @property \Phalcon\Di $di
 *
 */
trait CrudOperationsTrait
{
    /**
     * List records.
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
    public static function find(array $requestOptions = []) : array
    {
        $path = self::RESOURCE_NAME;
        $client = self::getClient();
        $params = $requestOptions;

        if (!empty($requestOptions)) {
            $path = $path . RequestOptions::parse($requestOptions);
        }

        return Util::convertToObject($client->call(CurlClient::METHOD_GET, $path, [], $params), self::class);
    }

    /**
     * Create record.
     *
     * @param string  $name
     * @param array  $roles
     *
     * @throws Exception
     *
     * @return array
     */
    public static function create(array $resourceFieldsValues) : object
    {
        $client = self::getClient();
        $params = $resourceFieldsValues;

        return Util::convertToObject($client->call(CurlClient::METHOD_POST, self::RESOURCE_NAME, [], $params), self::class);
    }

    /**
     * Find First by id or specified conditions.
     *
     * @param string  $teamId
     *
     * @throws Exception
     *
     * @return object
     */
    public static function findFirst(int $id = null, $requestOptions = [])
    {
        $path = self::RESOURCE_NAME;
        if (!is_null($id)) {
            $path = self::RESOURCE_NAME . '/' . $id;
        }

        if (!empty($requestOptions)) {
            $path = $path . RequestOptions::parse($requestOptions);
        }

        $client = self::getClient();
        $params = [];

        $response = Util::convertToObject($client->call(CurlClient::METHOD_GET, $path, [], $params), self::class);

        return !array_key_exists(0, $response) ? $response : current($response);
    }

    /**
     * Update record.
     *
     * @param string  $teamId
     * @param string  $name
     *
     * @throws Exception
     *
     * @return array
     */
    public static function update(int $id, array $resourceFieldsValues) : array
    {
        $client = self::getClient();
        $path = self::RESOURCE_NAME . '/' . $id;
        $params = $resourceFieldsValues;

        return Util::convertToObject($client->call(CurlClient::METHOD_PUT, $path, [], $params), self::class);
    }

    /**
     * Delete record.
     *
     * @param string  $id
     *
     * @throws Exception
     *
     * @return array
     */
    public static function delete(int $id) : array
    {
        $client = self::getClient();
        $path = self::RESOURCE_NAME . '/' . $id;
        $params = [];

        return $client->call(CurlClient::METHOD_DELETE, $path, [], $params);
    }
}
