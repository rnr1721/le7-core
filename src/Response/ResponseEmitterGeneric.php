<?php

declare(strict_types=1);

namespace Core\Response;

use Core\Interfaces\ResponseEmitter;
use Psr\Http\Message\ResponseInterface;

class ResponseEmitterGeneric implements ResponseEmitter
{

    private int $responseChunkSize = 4096;

    /**
     * Emit the response
     * @return void
     */
    public function emit(ResponseInterface $response): void
    {
        if (ob_get_contents()) {
            ob_clean();
        }

        // Emit code from Slim framework https://github.com/slimphp
        // Emit headers
        foreach ($response->getHeaders() as $name => $values) {
            $first = strtolower($name) !== 'set-cookie';
            foreach ($values as $value) {
                $header = sprintf('%s: %s', $name, $value);
                header($header, $first);
                $first = false;
            }
        }

        $statusLine = sprintf(
                'HTTP/%s %s %s',
                $response->getProtocolVersion(),
                $response->getStatusCode(),
                $response->getReasonPhrase()
        );
        header($statusLine, true, $response->getStatusCode());

        // Emit body
        $body = $response->getBody();
        if ($body->isSeekable()) {
            $body->rewind();
        }

        $amountToRead = (int) $response->getHeaderLine('Content-Length');
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
