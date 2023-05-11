<?php

declare(strict_types=1);

namespace Core;

use Core\Interfaces\Request;
use Core\Interfaces\Response;
use Core\Interfaces\RouteHttp;

class AppApi
{

    public Request $request;
    public Response $response;
    public RouteHttp $route;

    public function __construct(
            Request $request,
            Response $response,
            RouteHttp $route,
    )
    {
        $this->request = $request;
        $this->response = $response;
        $this->route = $route;
    }

}
