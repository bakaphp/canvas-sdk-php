<?php

namespace  Kanvas\Sdk\Routes;

use Kanvas\Sdk\Routes\PublicRoutes;
use Kanvas\Sdk\Routes\PrivateRoutes;

/**
 * Route Configurator Class
 */
class RouteConfigurator
{
    /**
     * Merge sent private routes with Kanvas default routes
     *
     * @param array $privateRoutes
     * @return array
     */
    public static function mergePrivateRoutes(array $privateRoutes): array
    {
        return array_merge(PrivateRoutes::getRoutes(), $privateRoutes);
    }

    /**
     * Merge sent private routes with Kanvas default routes
     *
     * @param array $publicRoutes
     * @return array
     */
    public static function mergePublicRoutes(array $publicRoutes): array
    {
        return array_merge(PublicRoutes::getRoutes(), $publicRoutes);
    }

}
