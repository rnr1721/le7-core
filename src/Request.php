<?php

declare(strict_types=1);

namespace Core;

use Core\Interfaces\RequestInterface;
use Core\Interfaces\ConfigInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * System request class
 * This is decorator for PSR ServerRequest,
 * but contain specific methods
 */
class Request implements RequestInterface
{

    /**
     * Config storage
     * @var ConfigInterface
     */
    private ConfigInterface $config;

    /**
     * PSR ServerRequest
     * @var ServerRequestInterface
     */
    private ServerRequestInterface $request;

    /**
     * Base public path
     * @var string|null
     */
    private string|null $basePath = null;

    /**
     * Base url
     * @var string|null
     */
    private string|null $baseUrl = null;

    /**
     * body parsers
     * @var array
     */
    private array $parsers = [
        'application/json' => 'parserJson',
        'application/x-www-form-urlencoded' => 'parserXurlformencoded'
    ];

    /**
     * Request Constructor
     * @param ServerRequestInterface $request PSR ServerRequest
     * @param ConfigInterface $config Config storage
     */
    public function __construct(
            ServerRequestInterface $request,
            ConfigInterface $config
    )
    {
        $this->request = $request;
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function getProtocolVersion(): string
    {
        return $this->request->getProtocolVersion();
    }

    /**
     * @inheritDoc
     */
    public function withProtocolVersion($version): static
    {
        $clone = clone $this;
        $clone->request = $this->request->withProtocolVersion($version);
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): array
    {
        return $this->request->getHeaders();
    }

    /**
     * @inheritDoc
     */
    public function hasHeader($name): bool
    {
        return $this->request->hasHeader($name);
    }

    /**
     * @inheritDoc
     */
    public function getHeader($name): array
    {
        return $this->request->getHeader($name);
    }

    /**
     * @inheritDoc
     */
    public function getHeaderLine($name): string
    {
        return $this->request->getHeaderLine($name);
    }

    /**
     * @inheritDoc
     */
    public function withHeader($name, $value): static
    {
        $clone = clone $this;
        $clone->request = $this->request->withHeader($name, $value);
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withAddedHeader($name, $value): static
    {
        $clone = clone $this;
        $clone->request = $this->request->withAddedHeader($name, $value);
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withoutHeader($name): static
    {
        $clone = clone $this;
        $clone->request = $this->request->withoutHeader($name);
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getBody(): StreamInterface
    {
        return $this->request->getBody();
    }

    /**
     * @inheritDoc
     */
    public function withBody(StreamInterface $body): static
    {
        $clone = clone $this;
        $clone->request = $this->request->withBody($body);
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getRequestTarget(): string
    {
        return $this->request->getRequestTarget();
    }

    /**
     * @inheritDoc
     */
    public function withRequestTarget($requestTarget): static
    {
        $clone = clone $this;
        $clone->request = $this->request->withRequestTarget($requestTarget);
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): string
    {
        return $this->request->getMethod();
    }

    /**
     * @inheritDoc
     */
    public function withMethod($method): static
    {
        $clone = clone $this;
        $clone->request = $this->request->withMethod($method);
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getUri(): UriInterface
    {
        return $this->request->getUri();
    }

    /**
     * @inheritDoc
     */
    public function withUri(UriInterface $uri, $preserveHost = false): static
    {
        $clone = clone $this;
        $clone->request = $this->request->withUri($uri, $preserveHost);
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getServerParams(): array
    {
        return $this->request->getServerParams();
    }

    /**
     * @inheritDoc
     */
    public function getCookieParams(): array
    {
        return $this->request->getCookieParams();
    }

    /**
     * @inheritDoc
     */
    public function withCookieParams(array $cookies): static
    {
        $clone = clone $this;
        $clone->request = $this->request->withCookieParams($cookies);
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getQueryParams(): array
    {
        return $this->request->getQueryParams();
    }

    /**
     * @inheritDoc
     */
    public function withQueryParams(array $query): static
    {
        $clone = clone $this;
        $clone->request = $this->request->withQueryParams($query);
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getUploadedFiles(): array
    {
        return $this->request->getUploadedFiles();
    }

    /**
     * @inheritDoc
     */
    public function withUploadedFiles(array $uploadedFiles): static
    {
        $clone = clone $this;
        $clone->request = $this->request->withUploadedFiles($uploadedFiles);
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getParsedBody()
    {
        return $this->request->getParsedBody();
    }

    /**
     * @inheritDoc
     */
    public function withParsedBody($data): static
    {
        $clone = clone $this;
        $clone->request = $this->request->withParsedBody($data);
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return $this->request->getAttributes();
    }

    /**
     * @inheritDoc
     */
    public function getAttribute($name, $default = null)
    {
        return $this->request->getAttribute($name, $default);
    }

    /**
     * @inheritDoc
     */
    public function withAttribute($name, $value): static
    {
        $clone = clone $this;
        $clone->request = $this->request->withAttribute($name, $value);
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withoutAttribute($name): static
    {
        $clone = clone $this;
        $clone->request = $this->request->withoutAttribute($name);
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getBase(): string
    {
        if ($this->basePath === null) {
            $this->basePath = $this->config->string('publicSubdir', '/') . '/' ?? '/';
        }
        return '/' . ltrim($this->basePath, '/');
    }

    /**
     * @inheritDoc
     */
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
     * @inheritDoc
     */
    public function isAjax(): bool
    {
        return $this->request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest';
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
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
     * @inheritDoc
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

    /**
     * @inheritDoc
     */
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

    /**
     * @inheritDoc
     */
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

    /**
     * @inheritDoc
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
     * @inheritDoc
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
     * @inheritDoc
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
     * @inheritDoc
     */
    public function setAttribute(string $name, mixed $value): self
    {
        $this->request = $this->request->withAttribute($name, $value);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    /**
     * @inheritDoc
     */
    public function setRequest(ServerRequestInterface $request): self
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getVisitorIp(): string
    {
        $result = $this->getServerParam('REMOTE_ADDR');
        return is_string($result) ? $result : '';
    }

}
