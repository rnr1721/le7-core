<?php

return [
    'headers' => [
        'web' => [
            //no-referrer, no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
            'Referrer-Policy' => 'no-referrer-when-downgrade',
            'Content-Security-Policy' => "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' cdn.jsdelivr.net;",
            'Strict-Transport-Security' => 'max-age=7776000',
            'X-Content-Type-Options' => 'nosniff',
            //deny or SAMEORIGIN
            'X-Frame-Options' => 'SAMEORIGIN',
            'X-XSS-Protection' => '1; mode=block'
        ],
        'api' => [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS, PUT, DELETE, HEAD',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Headers' => 'Content-Type, Origin, Authorizations, User-Agent, Host, Authorization, Content-Length, Accept, X-Requested-With, X-Auth-Token, Content-Language, Source'
        ]
    ]
];
