<?php

namespace Kanvas\Sdk\Traits;

use Kanvas\Sdk\HttpClient\CurlClient;
use Kanvas\Sdk\Util\RequestOptions;

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

        return $client->call(CurlClient::METHOD_GET, $path, [], $params);
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
    public static function create(array $resourceFieldsValues) : array
    {
        $client = self::getClient();
        $params = $resourceFieldsValues;

        return $client->call(CurlClient::METHOD_POST, self::RESOURCE_NAME, [], $params);
    }

    /**
     * Find First by id or specified conditions.
     *
     * @param string  $teamId
     *
     * @throws Exception
     *
     * @return array
     */
    public static function findFirst(int $id = null, $requestOptions = []) : array
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

        return $client->call(CurlClient::METHOD_GET, $path, [], $params);
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

        return $client->call(CurlClient::METHOD_PUT, $path, [], $params);
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
