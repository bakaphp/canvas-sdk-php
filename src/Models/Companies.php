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
     * Set Resource Variable.
     *
     * @return string
     */
    protected static function getSource(): string
    {
        return CompaniesResource::class;
    }
}
