<?php

namespace Kanvas\Sdk;

use Kanvas\Sdk\HttpClient\CurlClient;

class Resources
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $resource;

    /**
     * @param Client $client
     */
    public function __construct(CurlClient $client)
    {
        $this->client = $client;
        $this->resource = '/' . get_class();
    }
}
