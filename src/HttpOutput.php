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

    /**
     * HttpOutput Constructor
     * @param ResponseBag $responseBag
     * @param UrlInterface $url
     * @param MessageCollectionInterface $messageCollection
     */
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
     * @inheritDoc
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
     * @inheritDoc
     */
    public function redirect(
            string|null $location = null,
            string|array|null $params = null,
            string|null $language = null,
            int $code = 301
    ): ResponseInterface
    {
        $url = $this->url->get($location, $params, $language);
        return $this->response->withHeader('Location', $url)->withStatus($code);
    }

    /**
     * @inheritDoc
     */
    public function redirectExternal(
            string $url,
            int $code = 301
    ): ResponseInterface
    {
        return $this->response->withHeader('Location', $url)->withStatus($code);
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public function toHtml(): HtmlResponse
    {
        return new HtmlResponse($this->response);
    }

    /**
     * @inheritDoc
     */
    public function toJson(): JsonResponse
    {
        return new JsonResponse($this->response, $this->messageCollection);
    }

    /**
     * @inheritDoc
     */
    public function toJsonp(): JsonpResponse
    {
        return new JsonpResponse($this->response, $this->messageCollection);
    }

    /**
     * @inheritDoc
     */
    public function toText(): TextResponse
    {
        return new TextResponse($this->response);
    }

}
