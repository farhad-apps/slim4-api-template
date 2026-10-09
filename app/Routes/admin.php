<?php

use Slim\App;
use App\Controllers\Admin;
use App\Middleware\AdminMiddleware;

return function (App $app) {
    // Admin Routes
    $app->group('/admin', function ($group) {
        // Users
        $group->get('/users', [Admin\UserController::class, 'index']);
        $group->get('/users/{id}', [Admin\UserController::class, 'show']);
        $group->post('/users', [Admin\UserController::class, 'store']);
        $group->put('/users/{id}', [Admin\UserController::class, 'update']);
        $group->delete('/users/{id}', [Admin\UserController::class, 'destroy']);
        
        // Statistics
        $group->get('/stats/resellers', [Admin\UserController::class, 'getResellerStats']);
    })->add(new AdminMiddleware()); // فقط Admin می‌تونه بره
};
