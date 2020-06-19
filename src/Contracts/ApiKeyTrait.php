<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Contracts;

use Exception;

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
trait ApiKeyTrait
{
    /**
     * Validate if KANVAS_SDK_API_KEY is set. If set then return the value.
     *
     * @return string
     */
    protected function getSdkKey() : string
    {
        if (empty(getenv('KANVAS_SDK_API_KEY'))) {
            throw new Exception('App needs to set KANVAS_SDK_API_KEY environmental variables to Run. Please review your enviorment variables.');
        }

        return getenv('KANVAS_SDK_API_KEY');
    }
}
