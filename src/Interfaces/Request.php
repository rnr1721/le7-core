<?php

declare(strict_types=1);

namespace Core\Interfaces;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

interface Request
{

    /**
     * Get base folder in public
     * @return string
     */
    public function getBase(): string;

    /**
     * Get base URL
     * @return string
     */
    public function getBaseUrl(): string;

    /**
     * return true if this request is AJAX
     * @return bool
     */
    public function isAjax(): bool;

    /**
     * Return true if https
     * @return bool
     */
    public function isSecure(): bool;

    /**
     * Get content length of request
     * @return int|null
     */
    public function getContentLength(): int|null;

    /**
     * Get content type of request
     * @return string
     */
    public function getContentType(): string;

    /**
     * Get _SERVER vars
     * @return array
     */
    public function getServerParams(): array;

    /**
     * Get some one _SERVER var
     * @param string $name Param name
     * @param string|null $default Default if not exists
     * @return string|null
     */
    public function getServerParam(string $name, string|null $default = null): string|null;

    /**
     * Get query params
     * @return array
     */
    public function getQueryParams(): array;

    /**
     * Get all params
     * @return array
     */
    public function getParams(): array;

    /**
     * Get parsed body from PSR request
     * @return null|array|object
     */
    public function getParsedBody(): null|array|object;

    /**
     * Get cookie parameter
     * @param string $key Name of cookie
     * @param string|null $default Default if not exists
     * @return string|null
     */
    public function getCookieParam(string $key, string|null $default = null): string|null;

    /**
     * Get current PSR ServerRequestInterface
     * @return ServerRequestInterface
     */
    public function getRequest(): ServerRequestInterface;

    /**
     * Get request body as stream
     * @return StreamInterface
     */
    public function getBody(): StreamInterface;

    public function getCookieParams(): array;

    /**
     * Get header by name
     * @param string $name
     * @return array
     */
    public function getHeader(string $name): array;

    /**
     * Get header value as string
     * @param string $name
     * @return string
     */
    public function getHeaderLine(string $name): string;

    /**
     * Get all headers
     * @return array
     */
    public function getHeaders(): array;

    /**
     * Get method - POST,PUT, DELETE, GET etc
     * @return string
     */
    public function getMethod(): string;

    /**
     * Get protocol HTTP version
     * @return string
     */
    public function getProtocolVersion(): string;

    /**
     * Get request target
     * @return string
     */
    public function getRequestTarget(): string;

    /**
     * Get uploaded files array
     * @return array
     */
    public function getUploadedFiles(): array;

    /**
     * Get PSR UriInterface from ServerRequest
     * @return UriInterface
     */
    public function getUri(): UriInterface;

    /**
     * If header exists
     * @param string $name Header name
     * @return bool
     */
    public function hasHeader(string $name): bool;

    /**
     * Get some GET parameter
     * @param string $name Param name
     * @param string|int|bool|null $default Default if not exists
     * @return string|array|int|bool|null
     */
    public function wg(string $name, string|int|bool|null $default = null): string|array|int|bool|null;

    /**
     * Get some POST/PUT/DELETE etc parameter
     * @param string $name Param name
     * @param string|int|bool|null $default Default if not exists
     * @return string|array|int|bool|null
     */
    public function wp(string $name, string|int|bool|null $default = null): string|array|int|bool|null;

    /**
     * Get Request attributes
     * @return array
     */
    public function getAttributes(): array;

    /**
     * Get one Request attribute
     * @param string $param Attribute name
     * @param mixed $default Default if not exists
     * @return mixed
     */
    public function getAttribute(string $param, mixed $default = null): mixed;

    /**
     * Set request attribute
     * @param string $name
     * @param mixed $value
     * @return self
     */
    public function setAttribute(string $name, mixed $value): self;

    /**
     * Set new PSR ServerRequestInterface
     * @param ServerRequestInterface $request
     * @return self
     */
    public function setRequest(ServerRequestInterface $request): self;

    /**
     * Get visitor IP address or empty string
     * @return string
     */
    public function getVisitorIp(): string;
}
