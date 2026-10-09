<?php

use Slim\App;

return function (App $app) {
    $app->get('/customer', function ($request, $response) {
        $response->getBody()->write(json_encode([
            'status' => 'success',
            'message' => 'Customer routes loaded',
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    });
};
