<?php

declare(strict_types=1);

namespace le7\Core\Request;

use le7\Core\Config\ConfigInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\ServerRequestInterface;

class Request
{

    private ConfigInterface $config;
    private ServerRequestInterface $request;
    private UriInterface $uri;
    private array $serverParams;
    private array|null $queryParams = null;
    private array|null $params = null;
    private string $method;
    private string|null $basePath = null;
    private array $parsers = [
        'application/json' => 'parserJson',
        'application/x-www-form-urlencoded' => 'parserXurlformencoded'
    ];
    private string|null $visitorIp = null;

    public function __construct(ServerRequestInterface $request, ConfigInterface $config)
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
            $path = dirname($this->getServerParams()['SCRIPT_NAME']);
            $this->basePath = str_replace(['\\', ' '], ['/', '%20'], $path);
        }
        return $this->basePath;
    }

    /**
     * Delete the cookie by name
     * @param string $name
     * @return bool
     */
    public function unsetCookie(string $name): bool
    {
        if (isset($_COOKIE[$name])) {
            unset($_COOKIE[$name]);
            return setcookie($name, "", [
                'expires' => -1,
                'path' => '/',
                'secure' => $this->isSecure(),
                'samesite' => $this->config->getSessionCookieSamesite(),
            ]);
        } else {
            return false;
        }
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

    public function getContentLength(): int|null
    {
        $result = $this->serverRequest->getHeaderLine('Content-Length');
        if (($result === '')) {
            return null;
        }
        return intval($result);
    }

    /**
     * Return contents of header Content-Type
     * e.g. application/json
     * @return type
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
        if (!is_array($result)) {
            return null;
        }
        return $result;
    }

    public function getServerParams(): array
    {
        return $this->serverParams;
    }

    public function getServerParam(string $name, mixed $default = null): string
    {
        if (array_key_exists($name, $this->serverParams)) {
            return $this->serverParams[$name];
        }
        return $default;
    }

    public function getQueryParams(): array
    {
        if ($this->queryParams !== null) {
            return $this->queryParams;
        }
        $queryParams = [];
        parse_str($this->uri->getQuery(), $queryParams);
        if (is_array($queryParams)) {
            $this->queryParams = $queryParams;
        } else {
            $this->queryParams = [];
        }

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

    public function getCookieParam(string $key, $default = null)
    {
        $cookies = $this->request->getCookieParams();

        if (array_key_exists($key, $cookies)) {
            return $cookies[$key];
        }

        return $default;
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    public function getBody(): StreamInterface
    {
        return $this->request->getBody();
    }

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

    public function getHeaders(): array
    {
        return $this->request->getHeaders();
    }

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

    public function withAddedHeader(string $name, string|array $value)
    {
        $serverRequest = $this->request->withAddedHeader($name, $value);
        return new static($serverRequest);
    }

    public function withAttribute(string $name, mixed $value)
    {
        $serverRequest = $this->request->withAttribute($name, $value);
        return new static($serverRequest);
    }

    public function withAttributes(array $attributes)
    {
        $request = $this->request;

        foreach ($attributes as $attribute => $value) {
            $request = $request->withAttribute($attribute, $value);
        }

        return new static($request);
    }

    public function withoutAttribute(string $name)
    {
        $request = $this->request->withoutAttribute($name);
        return new static($request);
    }

    public function withBody(StreamInterface $body)
    {
        $request = $this->request->withBody($body);
        return new static($request);
    }

    public function withCookieParams(array $cookies)
    {
        $request = $this->request->withCookieParams($cookies);
        return new static($request);
    }

    public function withHeader(string $name, string|array $value)
    {
        $request = $this->request->withHeader($name, $value);
        return new static($request);
    }

    public function withoutHeader(string $name)
    {
        $request = $this->request->withoutHeader($name);
        return new static($request);
    }

    public function withMethod(string $method)
    {
        $request = $this->request->withMethod($method);
        return new static($request);
    }

    public function withParsedBody(null|array|object $data)
    {
        $request = $this->request->withParsedBody($data);
        return new static($request);
    }

    public function withProtocolVersion(string $version)
    {
        $request = $this->request->withProtocolVersion($version);
        return new static($request);
    }

    public function withQueryParams(array $query)
    {
        $request = $this->request->withQueryParams($query);
        return new static($request);
    }

    public function withRequestTarget(mixed $requestTarget)
    {
        $request = $this->request->withRequestTarget($requestTarget);
        return new static($request);
    }

    public function withUploadedFiles(array $uploadedFiles)
    {
        $request = $this->request->withUploadedFiles($uploadedFiles);
        return new static($request);
    }

    public function withUri(UriInterface $uri, bool $preserveHost = false)
    {
        $request = $this->request->withUri($uri, $preserveHost);
        return new static($request);
    }

    /**
     * Return parameter from $_GET array
     * if empty, it return second parameter $ifEmpty
     * @param string $name Name of parameter
     * @param string|bool|null $default Return if no data
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
     * @param string|bool|null $default Return if no data
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

    public function getVisitorIp(): string
    {

        if ($this->visitorIp !== null) {
            return $this->visitorIp;
        }

        // Get real visitor IP behind CloudFlare network
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client = $_SERVER['HTTP_CLIENT_IP'] ?? null;
        $forward = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null;
        $remote = $_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        $this->visitorIp = $ip;
        return $ip;
    }

    public function getAttributes(): array
    {
        return $this->request->getAttributes();
    }

    public function getAttribute(string $param, mixed $default = null): mixed
    {
        return $this->request->getAttribute($param, $default);
    }

    public function setAttribute(string $name, mixed $value)
    {
        $this->request = $this->request->withAttribute($name, $value);
    }

    public function setRequest(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

}
