<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Models;

use Kanvas\Sdk\Companies as CompaniesResource;

/**
 * Users Class.
 */
class Companies extends BaseModel
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
        self::setResource(CompaniesResource::class);
        return parent::find($params);
    }

    /**
     * Overwrite the user create function to return a usr object like we expect.
     *
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return object stdClass
     */
    public static function findFirst($params = null, $opts = null)
    {
        self::setResource(CompaniesResource::class);
        return parent::findFirst($params);
    }


}
