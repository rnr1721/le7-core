<?php

return [
    'state' => [
        'cookies' => [
            'domain' => '',
            'httpOnly' => true,
            'path' => '/',
            'isSecure' => false,
            'time' => 3600,
            'sameSite' => 'Lax'
        ],
        'session' => [
            'lifetime' => 86400,
            'path' => '/',
            'secure' => false,
            'httponly' => true,
            'samesite' => 'Lax'
        ]
    ]
];
