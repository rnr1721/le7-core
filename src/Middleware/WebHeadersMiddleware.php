<?php

declare(strict_types=1);

namespace Core\Middleware;

use Core\Interfaces\Config;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * This middleware add headers to PSR response in web routes
 */
class WebHeadersMiddleware implements MiddlewareInterface
{

    private Config $config;
    private array $defaultHeaders = [
        //no-referrer, no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
        'Referrer-Policy' => 'no-referrer-when-downgrade',
        'Content-Security-Policy' => "default-src 'self' 'unsafe-inline';img-src 'self' data:;font-src 'self'",
        'Strict-Transport-Security' => 'max-age=7776000',
        'X-Content-Type-Options' => 'nosniff',
        //deny or SAMEORIGIN
        'X-Frame-Options' => 'SAMEORIGIN',
        'X-XSS-Protection' => '1; mode=block'
    ];

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $headers = $this->config->array('headers.web') ?? $this->defaultHeaders;

        $response = $handler->handle($request);

        if ($headers) {
            foreach ($headers as $key => $value) {
                $response = $response->withHeader($key, $value);
            }
        }

        return $response;
    }

}
