<?php

namespace Kanvas\Routes;

use Baka\Router\Route;

/**
 * Default Kanvas Private Routes Class
 */
class PrivateRoutes
{
    private $privateRoutes = [
        Route::crud('/users')->controller('ApiController')->action('transporter')->notVia('post'),
        Route::crud('/companies')->controller('ApiController')->action('transporter'),
        Route::crud('/roles')->controller('ApiController')->action('transporter'),
        Route::crud('/locales')->controller('ApiController')->action('transporter'),
        Route::crud('/currencies')->controller('ApiController')->action('transporter'),
        Route::crud('/apps')->controller('ApiController')->action('transporter'),
        Route::crud('/notifications')->controller('ApiController')->action('transporter'),
        Route::crud('/system-modules')->controller('ApiController')->action('transporter'),
        Route::crud('/companies-branches')->controller('ApiController')->action('transporter'),
        Route::crud('/apps-plans')->controller('ApiController')->action('transporter'),
        Route::post('/apps-plans/{id}/reactivate')->controller('ApiController')->action('transporter'),
        Route::crud('/roles-acceslist')->controller('ApiController')->action('transporter'),
        Route::crud('/permissions-resources')->controller('ApiController')->action('transporter'),
        Route::crud('/permissions-resources-access')->controller('ApiController')->action('transporter'),
        Route::crud('/users-invite')->controller('ApiController')->action('transporter'),
        Route::crud('/devices')->controller('ApiController')->action('transporter'),
        Route::crud('/languages')->controller('ApiController')->action('transporter'),
        Route::crud('/webhooks')->controller('ApiController')->action('transporter'),
        Route::crud('/filesystem')->controller('ApiController')->action('transporter'),
        Route::get('/timezones')->controller('ApiController')->action('transporter'),
        Route::post('/notifications-read-all')->controller('ApiController')->action('transporter'),
        Route::post('/users/{id}/devices')->controller('ApiController')->action('transporter'),
        Route::delete('/users/{id}/devices/{deviceId}')->controller('ApiController')->action('transporter'),
        Route::delete('/filesystem/{id}/attributes/{name}')->controller('ApiController')->action('transporter'),
        Route::put('/filesystem-entity/{id}')->controller('ApiController')->action('transporter'),
        Route::put('/auth/logout')->controller('ApiController')->action('transporter'),
        Route::post('/users/invite')->controller('ApiController')->action('transporter'),
        Route::post('/roles-acceslist/{id}/copy')->controller('ApiController')->action('transporter'),
        Route::get('/custom-fields-modules/{id}/fields')->controller('ApiController')->action('transporter'),
        Route::put('/apps-plans/{id}/method')->controller('ApiController')->action('transporter'),
        Route::get('/schema/{slug}')->controller('ApiController')->action('transporter'),
        Route::get('/schema/{slug}/description')->controller('ApiController')->action('transporter'),
        Route::post('/users/{hash}/change-email')->controller('ApiController')->action('transporter'),
        Route::post('/users/{id}/request-email-change')->controller('ApiController')->action('transporter'),
        Route::put('/users/{id}/apps/{appsId}/status')->controller('ApiController')->action('transporter'),
        Route::get('/companies-groups')->controller('ApiController')->action('transporter'),
        Route::get('/companies-groups/{id}')->controller('ApiController')->action('transporter')
    ];

    /**
     * Get all Public Routes
     *
     * @return array
     */
    public static function getRoutes(): array
    {
        return self::$privateRoutes;
    }
}
