<?php
use Cake\Routing\Router;

Router::plugin(
    'NotificationWithPlivo',
    ['path' => '/notification-with-plivo'],
    function ($routes) {
        $routes->fallbacks('DashedRoute');
    }
);
