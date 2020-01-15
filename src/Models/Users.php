<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Models;

use Kanvas\Sdk\Users as UserResource;

/**
 * Users Class.
 */
class Users extends BaseModel
{
    /**
     * Overwrite the user create function to return a usr object like we expect.
     *
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return object stdClass
     */
    public static function find($params = null, $opts = null)
    {
        parent::setResource(UserResource::class);
        return parent::find($params);
    }


}
