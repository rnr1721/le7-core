<?php

namespace Core\Interfaces;

use Core\Response\JsonResponse;
use Core\Response\TextResponse;
use Core\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;

/**
 * @method mixed __get(string $name) Magic method to get property
 * @param JsonResponse $json
 * @property-read HtmlResponse $html
 * @property-read TextResponse $text
 */
interface Response
{

    /**
     * Magick method for get properties
     * @param string $name
     */
    public function __get(string $name);

    /**
     * Redirect to another internal page
     * @param string $location for example 'page/contacts'
     * @param string $params Params, for example "?name=john&age=33"
     * @param string $route Route, for example "admin" (see ./config/routes.php)
     * @param string $language Language for form link. Empty = default
     * @param int $code Response code
     * @return ResponseInterface
     */
    public function redirect(string $location = '', string $params = '', string $route = '', string $language = '', int $code = 301): ResponseInterface;

    /**
     * Redirect to any external page
     * @param string $url Url to redirect
     * @param int $code Response code
     * @return ResponseInterface
     */
    public function redirectExternal(string $url, int $code = 301): ResponseInterface;

    /**
     * Update PSR Response in object
     * @param ResponseInterface $response PSR Response
     * @return self
     */
    public function updateResponse(ResponseInterface $response): self;

    /**
     * Get current PSR Response
     * @return ResponseInterface
     */
    public function getPsrResponse(): ResponseInterface;

    /**
     * Get JsonResponse for emit in JSON
     * @return JsonResponse
     */
    public function toJson(): JsonResponse;

    /**
     * Get HtmlReponse for emit in Html
     * @return HtmlResponse
     */
    public function toHtml(): HtmlResponse;

    /**
     * Get Text Response for emit in Text
     * @return TextResponse
     */
    public function toText(): TextResponse;
}
