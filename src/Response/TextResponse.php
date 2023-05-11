<?php

declare(strict_types=1);

namespace Core\Response;

use Psr\Http\Message\ResponseInterface;

class TextResponse
{ 
    
    private ResponseInterface $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function __invoke(string $text, int|null $code = null, string $type = "text/plain"): ResponseInterface
    {
        return $this->emit($text, $code, $type);
    }

    /**
     * Emit text data with header Content-type
     * @param string $text String text data for emit
     * @param int|null $code Server response code
     * @param string $type Content-type for header
     * @return ResponseInterface
     */
    public function emit(string $text, int|null $code = null, string $type = "text/plain"): ResponseInterface
    {
        $this->response = $this->response->withHeader('Content-Type', $type);
        return $this->emitWithoutHeaders($text, $code);
    }

    /**
     * Emit text data for download with headers for download
     * @param string $text String text data for emit
     * @param int|null $code Server response code
     * @param string $fileName Filename for download
     * @param string $type Content-type for header
     * @return ResponseInterface
     */
    public function emitForDownload(string $text, int|null $code = 201, string $fileName = 'download', string $type = "text/plain"): ResponseInterface
    {

        $headers = [
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Pragma' => 'public',
            'Cache-Control' => 'must-revalidate'
        ];
        foreach ($headers as $hk => $hv) {
            $this->response = $this->response->withHeader($hk, $hv);
        }

        return $this->emit($text, $code, $type);
    }

    /**
     * Emit text without headers
     * @param string $text String text data for emit
     * @param int|null $code Server response code
     * @return ResponseInterface
     */
    public function emitWithoutHeaders(string $text, int|null $code = null): ResponseInterface
    {
        $this->response->getBody()->write($text);
        if ($code !== null) {
            return $this->response->withStatus($code);
        }
        return $this->response;
    }

}
