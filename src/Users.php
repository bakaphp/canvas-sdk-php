<?php

declare(strict_types=1);

namespace Kanvas\Sdk;

use Kanvas\Sdk\Api\Operations\All;
use Kanvas\Sdk\Api\Operations\Create;
use Kanvas\Sdk\Api\Operations\Delete;
use Kanvas\Sdk\Api\Operations\Update;
use Kanvas\Sdk\Api\Operations\Retrieve;
use Kanvas\Sdk\Api\Resource;
use Kanvas\Sdk\Util\Util;

class Users extends Resource
{
    const OBJECT_NAME = 'users';

    use All;
    use Create;
    use Delete;
    use Update;
    use Retrieve;

    /**
     * Overwrite the user create function to return a usr object like we expect.
     *
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return object stdClass
     */
    public static function create($params = null, $opts = null): object
    {
        self::_validateParams($params);
        $url = static::classUrl();
        list($response, $opts) = static::_staticRequest('post', $url, $params, $opts);

        $user = $response->data['user'];
        $user['session'] = $response->data['session'];
        return Util::convertToSimpleObject($user, $opts, self::OBJECT_NAME);
    }

    /**
     * Get the current Users Session.
     *
     * @return Users
     */
    public static function getSelf(): self
    {
        return self::retrieve('0');
    }
}
