<?php

use Slim\App;
use App\Controllers\Reseller;
use App\Middleware\ResellerMiddleware;

return function (App $app) {
    // Reseller Routes
    $app->group('/reseller', function ($group) {
        // Customers
        $group->get('/users', [Reseller\UserController::class, 'index']);
        $group->get('/users/{id}', [Reseller\UserController::class, 'show']);
        $group->post('/users', [Reseller\UserController::class, 'store']);
    })->add(new ResellerMiddleware()); // فقط Reseller می‌تونه بره
};
