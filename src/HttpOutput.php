<?php

namespace Core;

use Core\Interfaces\HttpOutputInterface;
use Core\Bag\ResponseBag;
use Core\Interfaces\UrlInterface;
use Core\Interfaces\MessageCollectionInterface;
use Core\Response\HtmlResponse;
use Core\Response\JsonResponse;
use Core\Response\JsonpResponse;
use Core\Response\TextResponse;
use Psr\Http\Message\ResponseInterface;

/**
 * @param JsonResponse $json
 * @param HtmlResponse $html
 * @param TextResponse $text
 */
class HttpOutput implements HttpOutputInterface
{

    /**
     * @var ResponseInterface
     */
    private ResponseInterface $response;

    /**
     * @var UrlInterface
     */
    private UrlInterface $url;

    /**
     * Message collection
     * @var MessageCollectionInterface
     */
    private MessageCollectionInterface $messageCollection;

    public function __construct(
            ResponseBag $responseBag,
            UrlInterface $url,
            MessageCollectionInterface $messageCollection
    )
    {
        $this->response = $responseBag->getResponse();
        $this->url = $url;
        $this->messageCollection = $messageCollection;
    }

    /**
     * Get ResponseInterface generators as properties
     * @param string $name Name of response generator
     */
    public function __get(string $name)
    {
        return match ($name) {
            'json' => new JsonResponse($this->response, $this->messageCollection),
            'html' => new HtmlResponse($this->response),
            'text' => new TextResponse($this->response)
        };
    }

    /**
     * Redirect to another internal page
     * @param string $location for example 'page/contacts'
     * @param string $params Params, for example "?name=john&age=33"
     * @param string $language Language for form link. Empty = default
     * @param int $code Response code
     * @return ResponseInterface
     */
    public function redirect(
            string $location = '',
            string $params = '',
            string $language = '',
            int $code = 301
    ): ResponseInterface
    {
        $url = $this->url->get($location, $params, $language);
        return $this->response->withHeader('Location', $url)->withStatus($code);
    }

    /**
     * Redirect to any external page
     * @param string $url Url to redirect
     * @param int $code Response code
     * @return ResponseInterface
     */
    public function redirectExternal(
            string $url,
            int $code = 301
    ): ResponseInterface
    {
        return $this->response->withHeader('Location', $url)->withStatus($code);
    }

    /**
     * Replace ResponseInterface to another one
     * @param ResponseInterface $response
     * @return self
     */
    public function updateResponse(ResponseInterface $response): self
    {
        $this->response = $response;
        return $this;
    }

    public function getPsrResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * Get HTML response generator
     * It can generate ResponseInterface with text/html header
     * from string
     * @return HtmlResponse
     */
    public function toHtml(): HtmlResponse
    {
        return new HtmlResponse($this->response);
    }

    /**
     * Get JSON response generator
     * It can generate ResponseInterface with application/json header
     * from string
     * @return JsonResponse
     */
    public function toJson(): JsonResponse
    {
        return new JsonResponse($this->response, $this->messageCollection);
    }

    /**
     * Get JSONP response generator
     * It can generate ResponseInterface for JSONP
     * from string
     * @return JsonpResponse
     */
    public function toJsonp(): JsonpResponse
    {
        return new JsonpResponse($this->response, $this->messageCollection);
    }

    /**
     * Get Text response generator
     * It can generate ResponseInterface with text/plain header
     * from string
     * @return TextResponse
     */
    public function toText(): TextResponse
    {
        return new TextResponse($this->response);
    }

}
