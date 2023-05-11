<?php

declare(strict_types=1);

namespace Core;

use Core\Interfaces\Request;
use Core\Interfaces\Response;
use Core\Interfaces\RouteHttp;
use Core\Interfaces\WebPage;
use Core\Interfaces\View;

class AppWeb
{

    public Request $request;
    public Response $response;
    public RouteHttp $route;
    public WebPage $webPage;
    public View $view;

    public function __construct(
            Request $request,
            Response $response,
            RouteHttp $route,
            WebPage $webPage,
            View $view
    )
    {
        $this->request = $request;
        $this->response = $response;
        $this->route = $route;
        $this->webPage = $webPage;
        $this->view = $view;
    }

}
