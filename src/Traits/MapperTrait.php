<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Traits;

use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use AutoMapperPlus\MapperInterface;

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
trait MapperTrait
{
    /**
     * Configure Auto Mapper.
     *
     * @param string $classToMap
     * @param string $dtoClass
     * @param string $mapperClass
     *
     * @return AutoMapperConfig
     */
    public function configureAutoMapper(string $classToMap, string $dtoClass, MapperInterface $mapperClass) : AutoMapperConfig
    {
        $config = new AutoMapperConfig();
        $config->getOptions()->dontSkipConstructor();
        $config->registerMapping($classToMap, $dtoClass)->useCustomMapper($mapperClass);

        return $config;
    }

    /**
     * Instantiate a new Auto Mapper.
     *
     * @param string $mapperClass
     *
     * @return AutoMapper
     */
    public function instantiateAutoMapper($config) : AutoMapper
    {
        return new AutoMapper($config);
    }
}
