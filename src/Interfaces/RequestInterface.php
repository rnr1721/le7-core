<?php

declare(strict_types=1);

namespace Core\Interfaces;

use Psr\Http\Message\ServerRequestInterface;

interface RequestInterface extends ServerRequestInterface
{

    /**
     * Return base path as string (subfolder)
     * This is not implementation of PSR ServerRequestInterface
     * @return string
     */
    public function getBase(): string;

    /**
     * Return base url
     * @return string
     */
    public function getBaseUrl(): string;

    /**
     * Return true if request is XMLHttpRequest (XHR)
     * @return bool
     */
    public function isAjax(): bool;

    /**
     * Return bool value, is secure scheme or not
     * @return bool
     */
    public function isSecure(): bool;

    /**
     * Get content length
     * @return int|null
     */
    public function getContentLength(): int|null;

    /**
     * Return contents of header Content-Type
     * e.g. application/json
     * @return string Content-Type or empty string
     */
    public function getContentType(): string;

    public function getServerParam(
            string $name,
            string|null $default = null
    ): string|null;

    /**
     * Return $_POST, php://input and form-data and other
     * request parametres compiled as array
     * @return array
     */
    public function getParams(): array;

    /**
     * Get cookie param or or something default if null
     * @param string $key
     * @param string|null $default
     * @return string|null
     */
    public function getCookieParam(
            string $key,
            string|null $default = null
    ): string|null;

    /**
     * Return parameter from $_GET array
     * if empty, it return second parameter $ifEmpty
     * @param string $name Name of parameter
     * @param string|int|bool|null $default Return if no data
     * @return string|array|int|bool|null
     */
    public function wg(
            string $name,
            string|int|bool|null $default = null
    ): string|array|int|bool|null;

    /**
     * Return parameter from $_POST or form-data or form-www-url-encoding array
     * if empty, it return second parameter $ifEmpty
     * @param string $name Name of parameter
     * @param string|int|bool|null $default Return if no data
     * @return string|array|int|bool|null
     */
    public function wp(
            string $name,
            string|int|bool|null $default = null
    ): string|array|int|bool|null;

    /**
     * Set server request attribure
     * @param string $name
     * @param mixed $value
     */
    public function setAttribute(string $name, mixed $value): self;

    /**
     * Set current PSR7 ServerRequest
     * @param ServerRequestInterface $request
     */
    public function setRequest(ServerRequestInterface $request): self;

    /**
     * Get current standard PSR7 request
     * @return ServerRequestInterface
     */
    public function getRequest(): ServerRequestInterface;

    /**
     * Get Visitor IP
     * @return string IP address or empty
     */
    public function getVisitorIp(): string;
}
