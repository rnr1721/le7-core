<?php

declare(strict_types=1);

namespace Core;

use Core\Interfaces\RequestInterface;
use Core\Interfaces\HttpOutputInterface;
use Core\Interfaces\RouteHttpInterface;
use Core\Interfaces\WebPageInterface;
use Core\Interfaces\ViewInterface;

class AppWeb
{

    public RequestInterface $request;
    public HttpOutputInterface $response;
    public RouteHttpInterface $route;
    public WebPageInterface $webPage;
    public ViewInterface $view;

    public function __construct(
            RequestInterface $request,
            HttpOutputInterface $response,
            RouteHttpInterface $route,
            WebPageInterface $webPage,
            ViewInterface $view
    )
    {
        $this->request = $request;
        $this->response = $response;
        $this->route = $route;
        $this->webPage = $webPage;
        $this->view = $view;
    }

}
