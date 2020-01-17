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
     * Set Resource Variable.
     *
     * @return string
     */
    protected static function getSource(): string
    {
        return UserResource::class;
    }
}
