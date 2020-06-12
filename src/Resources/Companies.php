<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Resources;

use Kanvas\Sdk\HttpClient\CurlClient;
use Kanvas\Sdk\Resources;
use Kanvas\Sdk\Traits\CrudOperationsTrait;

class Companies extends Resources
{
    /**
     * @param Client $client
     */
    public function __construct(CurlClient $client)
    {
        $this->client = $client;
        $this->resource = '/' . 'companies';
    }

    use CrudOperationsTrait;
}
