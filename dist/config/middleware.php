<?php

use App\Middleware\WebMenuMiddleware;
use Core\Middleware\WebPutMessagesMiddleware;
use Core\Middleware\ContentLengthMiddleware;
use Core\Middleware\ControllerRunMiddleware;
use Core\Middleware\WebSlashRedirectMiddleware;
use Core\Middleware\WebCsrfMiddleware;
use Core\Middleware\WebHandleMessagesMiddleware;
use Core\Middleware\ApiHeadersMiddleware;
use Core\Middleware\ApiOptionsMiddleware;
use Core\Middleware\WebSessionStartMiddleware;
use Core\Middleware\WebEmitCacheMiddleware;
use Core\Middleware\WebHeadersMiddleware;

return [
    'runner' => ControllerRunMiddleware::class,
    'middleware' => [
        'web' => [
            WebSlashRedirectMiddleware::class,
            WebHeadersMiddleware::class,
            WebEmitCacheMiddleware::class,
            WebSessionStartMiddleware::class,
            WebHandleMessagesMiddleware::class,
            WebCsrfMiddleware::class,
            WebMenuMiddleware::class,
            ControllerRunMiddleware::class,
            WebPutMessagesMiddleware::class,
            ContentLengthMiddleware::class
        ],
        'api' => [
            ApiHeadersMiddleware::class,
            ApiOptionsMiddleware::class,
            ControllerRunMiddleware::class,
            ContentLengthMiddleware::class
        ]
    ]
];
