<?php

use Slim\App;
use App\Controllers\Customer;
use App\Middleware\CustomerMiddleware;

return function (App $app) {
    // Customer Routes
    $app->group('/customer', function ($group) {
        // Profile
        $group->get('/profile', [Customer\ProfileController::class, 'show']);
        $group->put('/profile', [Customer\ProfileController::class, 'update']);
    })->add(new CustomerMiddleware()); // فقط Customer می‌تونه بره
};
