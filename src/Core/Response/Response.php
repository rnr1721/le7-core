<?php

namespace le7\Core\Response;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

class Response {

    public ResponseFactoryInterface $responseFactory;
    public ResponseInterface $responsePsr7;
    private $responseChunkSize = 4096;

    public function __construct(ResponseFactoryInterface $responseFactory) {
        $this->responsePsr7 = $responseFactory->createResponse();
        $this->responseFactory = $responseFactory;
    }

    public function setAddedHeader(string $header, string $value): self {
        $this->responsePsr7 = $this->responsePsr7->withAddedHeader($header, $value);
        return $this;
    }

    /**
     * Add the header to response
     * @param string $header Header name
     * @param string $value Header value
     * @return self
     */
    public function setHeader(string $header, string $value): self {
        $this->responsePsr7 = $this->responsePsr7->withHeader($header, $value);
        return $this;
    }

    /**
     * Get all headers as array
     * @return array
     */
    public function getHeaders(): array {
        return $this->responsePsr7->getHeaders();
    }

    /**
     * Set the response body as string data
     * If new = true - old body, headers and response code will be cleared
     * If new = false then data will be append to exist body
     * @param string $body String data for body
     * @param bool $new Make new instance of response
     * @return self
     */
    public function setBody(string $body, bool $new = false): self {
        if ($new) {
            $this->responsePsr7 = $this->responseFactory->createResponse();
        }
        $this->responsePsr7->getBody()->write($body);
        return $this;
    }

    /**
     * Set server response code to response
     * @param int $code Server response code
     * @return self
     */
    public function setResponseCode(int $code): self {
        $this->responsePsr7 = $this->responsePsr7->withStatus($code);
        return $this;
    }

    /**
     * Get the current response code that ready for emit
     * @return int
     */
    public function getResponseCode(): int {
        return $this->responsePsr7->getStatusCode();
    }

    /**
     * Emit the response
     * @return void
     */
    public function emit(): void {
        if (ob_get_contents()) {
            ob_clean();
        }

        // Emit code from Slim framework https://github.com/slimphp
        // Emit headers
        foreach ($this->responsePsr7->getHeaders() as $name => $values) {
            $first = strtolower($name) !== 'set-cookie';
            foreach ($values as $value) {
                $header = sprintf('%s: %s', $name, $value);
                header($header, $first);
                $first = false;
            }
        }

        $statusLine = sprintf(
                'HTTP/%s %s %s',
                $this->responsePsr7->getProtocolVersion(),
                $this->responsePsr7->getStatusCode(),
                $this->responsePsr7->getReasonPhrase()
        );
        header($statusLine, true, $this->responsePsr7->getStatusCode());

        // Emit body
        $body = $this->responsePsr7->getBody();
        if ($body->isSeekable()) {
            $body->rewind();
        }

        $amountToRead = (int) $this->responsePsr7->getHeaderLine('Content-Length');
        if (!$amountToRead) {
            $amountToRead = $body->getSize();
        }


        if ($amountToRead) {
            while ($amountToRead > 0 && !$body->eof()) {
                $length = min($this->responseChunkSize, $amountToRead);
                $data = $body->read($length);
                echo $data;

                $amountToRead -= strlen($data);

                if (connection_status() !== CONNECTION_NORMAL) {
                    break;
                }
            }
        } else {
            while (!$body->eof()) {
                echo $body->read($this->responseChunkSize);
                if (connection_status() !== CONNECTION_NORMAL) {
                    break;
                }
            }
        }

        exit;
    }

}
