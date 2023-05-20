<?php

declare(strict_types=1);

namespace Core;

use Core\Interfaces\RequestInterface;
use Core\Interfaces\HttpOutputInterface;
use Core\Interfaces\RouteHttpInterface;

class AppApi
{

    public RequestInterface $request;
    public HttpOutputInterface $response;
    public RouteHttpInterface $route;

    public function __construct(
            RequestInterface $request,
            HttpOutputInterface $response,
            RouteHttpInterface $route,
    )
    {
        $this->request = $request;
        $this->response = $response;
        $this->route = $route;
    }

}
