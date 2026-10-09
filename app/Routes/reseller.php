<?php

use Slim\App;

return function (App $app) {
    $app->get('/reseller', function ($request, $response) {
        $response->getBody()->write(json_encode([
            'status' => 'success',
            'message' => 'Reseller routes loaded',
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    });
};
