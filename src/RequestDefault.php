<?php

declare(strict_types=1);

namespace Core;

use Core\Interfaces\Request;
use Core\Interfaces\Config;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\ServerRequestInterface;

class RequestDefault implements Request
{

    private Config $config;

    /**
     * Current PSR7 ServerRequest
     * @var ServerRequestInterface
     */
    private ServerRequestInterface $request;
    private UriInterface $uri;
    private array $serverParams;
    private array|null $queryParams = null;
    private array|null $params = null;
    private string $method;
    private string|null $basePath = null;
    private string|null $baseUrl = null;
    private array $parsers = [
        'application/json' => 'parserJson',
        'application/x-www-form-urlencoded' => 'parserXurlformencoded'
    ];
    private string|null $visitorIp = null;

    public function __construct(ServerRequestInterface $request, Config $config)
    {
        $this->request = $request;
        $this->serverParams = $_SERVER;
        $this->uri = $request->getUri();
        $this->method = $request->getMethod();
        $this->config = $config;
    }

    /**
     * Return base path as string (subfolder)
     * @return string
     */
    public function getBase(): string
    {
        if ($this->basePath === null) {
            $this->basePath = $this->config->string('publicSubdir', '/') . '/' ?? '/';
        }
        return '/' . ltrim($this->basePath, '/');
    }

    public function getBaseUrl(): string
    {
        if ($this->baseUrl === null) {
            $this->baseUrl = (string) $this->getUri()
                            ->withPath('')
                            ->withFragment('')
                            ->withQuery('') . rtrim($this->getBase(), '/');
        }
        return $this->baseUrl;
    }

    /**
     * Return true if request is XMLHttpRequest (XHR)
     * @return bool
     */
    public function isAjax(): bool
    {
        return $this->request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest';
    }

    /**
     * Return bool value, is secure scheme or not
     * @return bool
     */
    public function isSecure(): bool
    {
        $scheme = $this->getUri()->getScheme();
        if ($scheme === 'http') {
            return false;
        }
        return true;
    }

    /**
     * Get content length
     * @return int|null
     */
    public function getContentLength(): int|null
    {
        $result = $this->request->getHeaderLine('Content-Length');
        if (($result === '')) {
            return null;
        }
        return intval($result);
    }

    /**
     * Return contents of header Content-Type
     * e.g. application/json
     * @return string
     */
    public function getContentType(): string
    {
        return $this->request->getHeaderLine('Content-Type');
    }

    /**
     * Parser, if header Content-type json will be detected
     * @param string $data
     * @return array|null
     */
    private function parserJson(string $data): array|null
    {
        $result = json_decode($data, true);
        if (!is_array($result)) {
            return null;
        }
        return $result;
    }

    /**
     * Parser, if header Content-type x-www-form-urlencoded will be detected
     * @param string $data
     * @return array|null
     */
    private function parserXurlformencoded(string $data): array|null
    {
        $result = array();
        parse_str($data, $result);
        return $result;
    }

    /**
     * Get all params from $_SERVER
     * @return array
     */
    public function getServerParams(): array
    {
        return $this->serverParams;
    }

    /**
     * Get one param from $_SERVER
     * @param string $name Param name
     * @param string $default Default if not exists
     * @return string|null
     */
    public function getServerParam(string $name, string|null $default = null): string|null
    {
        if (array_key_exists($name, $this->serverParams)) {
            return $this->serverParams[$name];
        }
        return $default;
    }

    /**
     * Get all GET params from URL
     * @return array
     */
    public function getQueryParams(): array
    {
        if ($this->queryParams !== null) {
            return $this->queryParams;
        }
        $queryParams = [];
        parse_str($this->uri->getQuery(), $queryParams);
        $this->queryParams = $queryParams;
        return $this->queryParams;
    }

    /**
     * Return $_POST, php://input and form-data and other
     * request parametres compiled as array
     * @return array
     */
    public function getParams(): array
    {

        if ($this->params !== null) {
            return $this->params;
        }

        $body = $this->request->getParsedBody();

        $contentType = $this->getContentType();
        foreach ($this->parsers as $parserType => $parserMethod) {
            if (strpos($contentType, $parserType) === 0) {
                $params = $this->{$parserMethod}($this->request->getBody()->getContents());
                if (is_array($body) && is_array($params)) {
                    $body = array_merge($body, $params);
                } elseif (is_array($params)) {
                    $body = $params;
                }
            }
        }

        if (is_array($body)) {
            $this->params = $body;
            return $body;
        }
        $this->params = array();
        return $this->params;
    }

    public function getParsedBody(): null|array|object
    {
        return $this->request->getParsedBody();
    }

    /**
     * Get cookie param or or something default if null
     * @param string $key
     * @param string|null $default
     * @return string|null
     */
    public function getCookieParam(string $key, string|null $default = null): string|null
    {
        $cookies = $this->request->getCookieParams();

        if (array_key_exists($key, $cookies)) {
            return $cookies[$key];
        }

        return $default;
    }

    /**
     * Get Current PSR7 ServerRequest
     * @return ServerRequestInterface
     */
    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    /**
     * Get request body
     * @return StreamInterface
     */
    public function getBody(): StreamInterface
    {
        return $this->request->getBody();
    }

    /**
     * Get all cookie params as array
     * @return array
     */
    public function getCookieParams(): array
    {
        return $this->request->getCookieParams();
    }

    public function getHeader(string $name): array
    {
        return $this->request->getHeader($name);
    }

    public function getHeaderLine(string $name): string
    {
        return $this->request->getHeaderLine($name);
    }

    /**
     * Get all request headers as array
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->request->getHeaders();
    }

    /**
     * Get current method - GET, POST, PUT etc
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    public function getProtocolVersion(): string
    {
        return $this->request->getProtocolVersion();
    }

    public function getRequestTarget(): string
    {
        return $this->request->getRequestTarget();
    }

    public function getUploadedFiles(): array
    {
        return $this->request->getUploadedFiles();
    }

    public function getUri(): UriInterface
    {
        return $this->request->getUri();
    }

    public function hasHeader(string $name): bool
    {
        return $this->request->hasHeader($name);
    }

    /**
     * Return parameter from $_GET array
     * if empty, it return second parameter $ifEmpty
     * @param string $name Name of parameter
     * @param string|int|bool|null $default Return if no data
     * @return string|array|int|bool|null
     */
    public function wg(string $name, string|int|bool|null $default = null): string|array|int|bool|null
    {
        $params = $this->getQueryParams();
        if (array_key_exists($name, $params)) {
            return $params[$name];
        }
        return $default;
    }

    /**
     * Return parameter from $_POST or form-data or form-www-url-encoding array
     * if empty, it return second parameter $ifEmpty
     * @param string $name Name of parameter
     * @param string|int|bool|null $default Return if no data
     * @return string|array|int|bool|null
     */
    public function wp(string $name, string|int|bool|null $default = null): string|array|int|bool|null
    {
        $params = $this->getParams();
        if (array_key_exists($name, $params)) {
            return $params[$name];
        }
        return $default;
    }

    /**
     * Get all server request attributes
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->request->getAttributes();
    }

    /**
     * Get server request attribute
     * @param string $param
     * @param mixed $default
     * @return mixed
     */
    public function getAttribute(string $param, mixed $default = null): mixed
    {
        return $this->request->getAttribute($param, $default);
    }

    /**
     * Set server request attribure
     * @param string $name
     * @param mixed $value
     */
    public function setAttribute(string $name, mixed $value): self
    {
        $this->request = $this->request->withAttribute($name, $value);
        return $this;
    }

    /**
     * Set current PSR7 ServerRequest
     * @param ServerRequestInterface $request
     */
    public function setRequest(ServerRequestInterface $request): self
    {
        $this->request = $request;
        return $this;
    }

    public function getVisitorIp(): string
    {
        // проверяем наличие заголовка HTTP_X_FORWARDED_FOR
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // если он есть, разделяем значения по запятым и берем первый адрес
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($ips[0]);
        }

        // если заголовок не найден, проверяем наличие заголовка REMOTE_ADDR
        if (!empty($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }

        // возвращаем пустую строку, если IP адрес не удалось определить
        return '';
    }

}
