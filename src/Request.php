<?php

namespace Core;

declare(strict_types=1);

use Core\Interfaces\RequestInterface;
use Core\Interfaces\ConfigInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class Request implements RequestInterface
{

    private ConfigInterface $config;
    private ServerRequestInterface $request;
    private string|null $basePath = null;
    private string|null $baseUrl = null;
    private array $parsers = [
        'application/json' => 'parserJson',
        'application/x-www-form-urlencoded' => 'parserXurlformencoded'
    ];

    public function __construct(
            ServerRequestInterface $request,
            ConfigInterface $config
    )
    {
        $this->request = $request;
        $this->config = $config;
    }

    public function getProtocolVersion(): string
    {
        return $this->request->getProtocolVersion();
    }

    public function withProtocolVersion($version): static
    {
        $clone = clone $this;
        $clone->request = $this->request->withProtocolVersion($version);
        return $clone;
    }

    public function getHeaders(): array
    {
        return $this->request->getHeaders();
    }

    public function hasHeader($name): bool
    {
        return $this->request->hasHeader($name);
    }

    public function getHeader($name): array
    {
        return $this->request->getHeader($name);
    }

    public function getHeaderLine($name): string
    {
        return $this->request->getHeaderLine($name);
    }

    public function withHeader($name, $value): static
    {
        $clone = clone $this;
        $clone->request = $this->request->withHeader($name, $value);
        return $clone;
    }

    public function withAddedHeader($name, $value): static
    {
        $clone = clone $this;
        $clone->request = $this->request->withAddedHeader($name, $value);
        return $clone;
    }

    public function withoutHeader($name): static
    {
        $clone = clone $this;
        $clone->request = $this->request->withoutHeader($name);
        return $clone;
    }

    public function getBody(): StreamInterface
    {
        return $this->request->getBody();
    }

    public function withBody(StreamInterface $body): static
    {
        $clone = clone $this;
        $clone->request = $this->request->withBody($body);
        return $clone;
    }

    public function getRequestTarget(): string
    {
        return $this->request->getRequestTarget();
    }

    public function withRequestTarget($requestTarget): static
    {
        $clone = clone $this;
        $clone->request = $this->request->withRequestTarget($requestTarget);
        return $clone;
    }

    public function getMethod(): string
    {
        return $this->request->getMethod();
    }

    public function withMethod($method): static
    {
        $clone = clone $this;
        $clone->request = $this->request->withMethod($method);
        return $clone;
    }

    public function getUri(): UriInterface
    {
        return $this->request->getUri();
    }

    public function withUri(UriInterface $uri, $preserveHost = false): static
    {
        $clone = clone $this;
        $clone->request = $this->request->withUri($uri, $preserveHost);
        return $clone;
    }

    public function getServerParams(): array
    {
        return $this->request->getServerParams();
    }

    public function getCookieParams(): array
    {
        return $this->request->getCookieParams();
    }

    public function withCookieParams(array $cookies): static
    {
        $clone = clone $this;
        $clone->request = $this->request->withCookieParams($cookies);
        return $clone;
    }

    public function getQueryParams(): array
    {
        return $this->request->getQueryParams();
    }

    public function withQueryParams(array $query): static
    {
        $clone = clone $this;
        $clone->request = $this->request->withQueryParams($query);
        return $clone;
    }

    public function getUploadedFiles(): array
    {
        return $this->request->getUploadedFiles();
    }

    public function withUploadedFiles(array $uploadedFiles): static
    {
        $clone = clone $this;
        $clone->request = $this->request->withUploadedFiles($uploadedFiles);
        return $clone;
    }

    public function getParsedBody()
    {
        return $this->request->getParsedBody();
    }

    public function withParsedBody($data): static
    {
        $clone = clone $this;
        $clone->request = $this->request->withParsedBody($data);
        return $clone;
    }

    public function getAttributes(): array
    {
        return $this->request->getAttributes();
    }

    public function getAttribute($name, $default = null)
    {
        return $this->request->getAttribute($name, $default);
    }

    public function withAttribute($name, $value): static
    {
        $clone = clone $this;
        $clone->request = $this->request->withAttribute($name, $value);
        return $clone;
    }

    public function withoutAttribute($name): static
    {
        $clone = clone $this;
        $clone->request = $this->request->withoutAttribute($name);
        return $clone;
    }

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

    public function isAjax(): bool
    {
        return $this->request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest';
    }

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
        $result = $this->request->getHeaderLine('Content-Length');
        if (($result === '')) {
            return null;
        }
        return intval($result);
    }

    public function getContentType(): string
    {
        return $this->request->getHeaderLine('Content-Type');
    }

    /**
     * Parser, if header Content-type json will be detected
     * @param string $data
     * @return array|null
     */
    protected function parserJson(string $data): array|null
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
    protected function parserXurlformencoded(string $data): array|null
    {
        $result = array();
        parse_str($data, $result);
        return $result;
    }

    public function getServerParam(
            string $name,
            string|null $default = null
    ): string|null
    {
        $serverParams = $this->getServerParams();
        if (array_key_exists($name, $serverParams)) {
            return $serverParams[$name];
        }
        return $default;
    }

    public function getParams(): array
    {

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
            return $body;
        } elseif (is_object($body)) {
            return (array) $body;
        }
        return [];
    }

    public function getCookieParam(string $key, string|null $default = null): string|null
    {
        $cookies = $this->request->getCookieParams();

        if (array_key_exists($key, $cookies)) {
            return $cookies[$key];
        }

        return $default;
    }

    public function wg(string $name, string|int|bool|null $default = null): string|array|int|bool|null
    {
        $params = $this->getQueryParams();
        if (array_key_exists($name, $params)) {
            return $params[$name];
        }
        return $default;
    }

    public function wp(string $name, string|int|bool|null $default = null): string|array|int|bool|null
    {
        $params = $this->getParams();
        if (array_key_exists($name, $params)) {
            return $params[$name];
        }
        return $default;
    }

    public function setAttribute(string $name, mixed $value): self
    {
        $this->request = $this->request->withAttribute($name, $value);
        return $this;
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    public function setRequest(ServerRequestInterface $request): self
    {
        $this->request = $request;
        return $this;
    }

    public function getVisitorIp(): string
    {
        $result = $this->getServerParam('REMOTE_ADDR');
        return is_string($result) ? $result : '';
    }

}
