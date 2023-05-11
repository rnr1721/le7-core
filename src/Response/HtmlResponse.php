<?php

declare(strict_types=1);

namespace Core\Response;

use Psr\Http\Message\ResponseInterface;

class HtmlResponse
{

    private ResponseInterface $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function __invoke(string $html, int|null $code = null): ResponseInterface
    {
        return $this->emit($html, $code);
    }

    /**
     * Output HTML data with security headers
     * You can configure security headers in config.ini
     * @param string $html Html string to output
     * @param int|null $code Server response code
     * @return ResponseInterface
     */
    public function emit(string $html, int|null $code = null): ResponseInterface
    {
        $this->response = $this->response->withHeader('Content-Type', "text/html");
        return $this->emitWithoutHeaders($html, $code);
    }

    /**
     * Output HTML data without security headers
     * Only with Content-Type
     * @param string $html Html string to output
     * @param int|null $code Server response code
     * @return ResponseInterface
     */
    public function emitWithoutHeaders(string $html, int|null $code = null): ResponseInterface
    {
        $this->response->getBody()->write($html);
        if ($code !== null) {
            return $this->response->withStatus($code);
        }
        return $this->response;
    }

}
