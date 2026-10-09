<?php

use Slim\App;
use App\Controllers\UserController;

return function (App $app) {
    $app->get('/api/users', [UserController::class, 'index']);
    $app->get('/api/users/{id}', [UserController::class, 'show']);
    $app->post('/api/users', [UserController::class, 'store']);
    $app->put('/api/users/{id}', [UserController::class, 'update']);
    $app->delete('/api/users/{id}', [UserController::class, 'destroy']);
};
