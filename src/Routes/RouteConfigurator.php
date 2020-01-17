<?php

namespace  Kanvas\Routes;

use Kanvas\Routes\PublicRoutes;
use Kanvas\Routes\PrivateRoutes;

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
