<?php

declare(strict_types=1);

namespace Canvas;

use Canvas\Api\Operations\All;
use Canvas\Api\Operations\Create;
use Canvas\Api\Operations\Delete;
use Canvas\Api\Operations\Update;
use Canvas\Api\Operations\Retrieve;
use Canvas\Api\Resource;
use Canvas\Util\Util;

class Users extends Resource
{
    const OBJECT_NAME = 'users';

    use All;
    use Create;
    use Delete;
    use Update;
    use Retrieve;

    /**
     * Overwrite the user create function to return a usr object like we expect
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
}
