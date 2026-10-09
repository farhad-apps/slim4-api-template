<?php

use Slim\App;

return function (App $app) {
    $app->get('/admin', function ($request, $response) {
        $response->getBody()->write(json_encode([
            'status' => 'success',
            'message' => 'Admin routes loaded',
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    });
};
