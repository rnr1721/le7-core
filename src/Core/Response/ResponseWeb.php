<?php

namespace App\Core\Response;

use App\Core\Response\Response;
use App\Core\Response\Output\ResponseHtml;
use App\Core\Response\Output\ResponseJson;
use App\Core\Response\Output\ResponseText;

use App\Core\Helpers\UrlHelper;

class ResponseWeb extends ResponseMain implements ResponseInterface {

    protected UrlHelper $urlHelper;


    public function __construct(Response $response, ResponseHtml $html, ResponseJson $json, ResponseText $text, UrlHelper $urlHelper) {
        parent::__construct($response, $html, $json, $text);
        $this->urlHelper = $urlHelper;
    }

    /**
     * Redirect to another internal page
     * Usage: return $this->response->redirect("page","delete","?id=55");
     * @param string $controller Controller, for example "page". Empty = "index"
     * @param string $action Action, for example "delete". Empty = index
     * @param string $params Params, for example "?name=john&age=33"
     * @param string $route Route, for example "admin" (see ./config/routes.php)
     * @param string $language Language for form link. Empty = defaulr
     * @return int
     */
    public function redirect(string $controller = '', string $action = '', string $params = '', string $route = '', string $language = ''): int {
        $l = $this->urlHelper->get($controller, $action, $params, $route, $language);
        $this->response->setHeader('Location', $l);
        return 301;
    }

    /**
     * Redirect to any external page
     * @param string $url Url to redirect
     * @return int
     */
    public function redirectExternal(string $url): int {
        $this->response->setHeader('Location', $url);
        return 301;
    }
    
}
