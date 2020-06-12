<?php

namespace Kanvas\Sdk\Traits;

use Kanvas\Sdk\HttpClient\CurlClient;

/**
 * Trait ResponseTrait.
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
    public function find(array $requestOptions = []) : array
    {
        $params = $requestOptions;

        return $this->client->call(CurlClient::METHOD_GET, $this->resource, [
            'content-type' => 'application/json',
        ], $params);
    }

    /**
     * Create Team.
     *
     * Create a new team. The user who creates the team will automatically be
     * assigned as the owner of the team. The team owner can invite new members,
     * who will be able add new owners and update or delete the team from your
     * project.
     *
     * @param string  $name
     * @param array  $roles
     *
     * @throws Exception
     *
     * @return array
     */
    public function create(array $resourceFieldsValues) : array
    {
        $params = $resourceFieldsValues;

        return $this->client->call(CurlClient::METHOD_POST, $this->resource, [
            'content-type' => 'application/json',
        ], $params);
    }

    /**
     * Get Team.
     *
     * Get team by its unique ID. All team members have read access for this
     * resource.
     *
     * @param string  $teamId
     *
     * @throws Exception
     *
     * @return array
     */
    public function findFirst(int $id = null) : array
    {
        if (!is_null($id)) {
            $this->resource = $this->resource . '/' . $id;
        }
        $params = [];

        return $this->client->call(CurlClient::METHOD_GET, $this->resource, [
            'content-type' => 'application/json',
        ], $params);
    }

    /**
     * Update Team.
     *
     * Update team by its unique ID. Only team owners have write access for this
     * resource.
     *
     * @param string  $teamId
     * @param string  $name
     *
     * @throws Exception
     *
     * @return array
     */
    public function update(int $id, array $resourceFieldsValues) : array
    {
        $this->resource = $this->resource . '/' . $id;
        $params = $resourceFieldsValues;

        return $this->client->call(CurlClient::METHOD_PUT, $this->resource, [
            'content-type' => 'application/json',
        ], $params);
    }

    /**
     * Delete Team.
     *
     * Delete team by its unique ID. Only team owners have write access for this
     * resource.
     *
     * @param string  $id
     *
     * @throws Exception
     *
     * @return array
     */
    public function delete(int $id) : array
    {
        $this->resource = $this->resource . '/' . $id;
        $params = [];

        return $this->client->call(CurlClient::METHOD_DELETE, $this->resource, [
            'content-type' => 'application/json',
        ], $params);
    }
}
