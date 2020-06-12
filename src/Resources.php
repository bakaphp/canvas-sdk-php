<?php

namespace Kanvas\Sdk;

use Kanvas\Sdk\HttpClient\CurlClient;

class Resources
{
    /**
     * @var Client
     */
    protected $client;

    public static function getClient()
    {
        return CurlClient::getInstance();
    }
}
