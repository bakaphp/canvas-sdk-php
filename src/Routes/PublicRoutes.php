<?php

namespace Kanvas\Sdk\Routes;

use Baka\Router\Route;

/**
 * Default Kanvas Public Routes Class
 */
class PublicRoutes
{
    private $publicRoutes = [
        Route::get('/')->controller('ApiController')->action('transporter'),
        Route::post('/auth')->controller('ApiController')->action('transporter'),
        Route::post('/refresh-token')->controller('ApiController')->action('transporter'),
        Route::post('/users')->controller('ApiController')->action('transporter'),
        Route::post('/auth/forgot')->controller('ApiController')->action('transporter'),
        Route::post('/auth/reset/{key}')->controller('ApiController')->action('transporter'),
        Route::get('/users-invite/validate/{hash}')->controller('ApiController')->action('transporter'),
        Route::post('/users-invite/{hash}')->controller('ApiController')->action('transporter'),
        Route::post('/webhook/payments')->controller('ApiController')->action('transporter'),
        Route::get('/apps/{key}/settings')->controller('ApiController')->action('transporter'),
        Route::post('/users/social')->controller('ApiController')->action('transporter')
    ];

    /**
     * Get all Public Routes
     *
     * @return array
     */
    public static function getRoutes(): array
    {
        return self::$publicRoutes;
    }
}
