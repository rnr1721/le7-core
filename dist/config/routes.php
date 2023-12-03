<?php

return array(
    'routes' => [
        'admin' => [
            'key' => 'admin',
            'type' => 'web',
            'address' => '/admin',
            'namespace' => 'App\Controller\Web\Admin',
            'params' => 7,
            'multilang' => true
        ],
        'apiv1' => [
            'key' => 'apiv1',
            'type' => 'api',
            'address' => '/api/v1',
            'namespace' => 'App\Controller\Api\v1',
            'params' => 7,
            'multilang' => false
        ],
        'web' => [
            'key' => 'web',
            'type' => 'web',
            'address' => '/',
            'namespace' => 'App\Controller\Web',
            'params' => 7,
            'multilang' => true
        ]
    ]
);
