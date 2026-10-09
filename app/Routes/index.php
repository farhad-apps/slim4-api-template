<?php

use Slim\App;

return function (App $app) {
    require __DIR__ . '/api.php';
    require __DIR__ . '/admin.php';
    require __DIR__ . '/reseller.php';
    require __DIR__ . '/customer.php';
};
