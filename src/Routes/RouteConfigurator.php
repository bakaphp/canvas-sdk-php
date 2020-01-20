<?php

namespace  Kanvas\Sdk\Routes;

use Kanvas\Sdk\Routes\PublicRoutes;
use Kanvas\Sdk\Routes\PrivateRoutes;

/**
 * Route Configurator Class.
 */
class RouteConfigurator
{
    /**
     * Merge sent private routes with Kanvas default routes.
     *
     * @param string $path
     * @param array $privateRoutes
     * @return array
     * @todo search Kanvas Routes by file path.
     */
    public static function mergePrivateRoutes(array $privateRoutes, string $path = null): array
    {
        return array_merge(isset($path) ? require($path) : PrivateRoutes::getRoutes(), $privateRoutes);
    }

    /**
     * Merge sent private routes with Kanvas default routes.
     *
     * @param string $path
     * @param array $publicRoutes
     * @return array
     */
    public static function mergePublicRoutes(array $publicRoutes, string $path = null): array
    {
        return array_merge(isset($path) ? require($path) : PublicRoutes::getRoutes(), $publicRoutes);
    }
}
