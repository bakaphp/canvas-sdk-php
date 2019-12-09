<?php

/**
 * Enabled providers. Order does matter.
 */

use Kanvas\Sdk\Providers\CacheDataProvider;
use Kanvas\Sdk\Providers\ConfigProvider;
use Kanvas\Sdk\Providers\DatabaseProvider;
use Kanvas\Sdk\Providers\ErrorHandlerProvider;
use Kanvas\Sdk\Providers\LoggerProvider;
use Kanvas\Sdk\Providers\ModelsMetadataProvider;
use Kanvas\Sdk\Providers\SessionProvider;
use Kanvas\Sdk\Providers\QueueProvider;
use Kanvas\Sdk\Providers\MailProvider;
use Kanvas\Sdk\Providers\RedisProvider;
use Kanvas\Sdk\Providers\RequestProvider;
use Kanvas\Sdk\Providers\AclProvider;
use Kanvas\Sdk\Providers\AppProvider;
use Kanvas\Sdk\Providers\ResponseProvider;
use Kanvas\Sdk\Providers\FileSystemProvider;
use Kanvas\Sdk\Providers\EventsManagerProvider;
use Kanvas\Sdk\Providers\MapperProvider;
use Kanvas\Sdk\Providers\ElasticProvider;
use Kanvas\Sdk\Providers\RegistryProvider;

return [
    ConfigProvider::class,
    LoggerProvider::class,
    ErrorHandlerProvider::class,
    DatabaseProvider::class,
    ModelsMetadataProvider::class,
    RequestProvider::class,
    CacheDataProvider::class,
    SessionProvider::class,
    QueueProvider::class,
    MailProvider::class,
    RedisProvider::class,
    AclProvider::class,
    AppProvider::class,
    ResponseProvider::class,
    FileSystemProvider::class,
    EventsManagerProvider::class,
    MapperProvider::class,
    ElasticProvider::class,
    RegistryProvider::class
];
