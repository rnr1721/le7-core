<?php

namespace le7\Core\Response\Output;

class ResponseText extends ResponseOutput {

    public function __invoke(string $text, int|null $code = null, string $type = "text/plain") {
        $this->emit($text, $code, $type);
    }

    /**
     * Emit text data with header Content-type
     * @param string $text String text data for emit
     * @param int|null $code Server response code
     * @param string $type Content-type for header
     */
    public function emit(string $text, int|null $code = null, string $type = "text/plain") {
        $this->response->setHeader('Content-Type', $type);
        $this->emitWithoutHeaders($text, $code);
    }

    /**
     * Emit text data for download with headers for download
     * @param string $text String text data for emit
     * @param int|null $code Server response code
     * @param string $filename Filename for download
     * @param string $type Content-type for header
     */
    public function emitForDownload(string $text, int|null $code = 201, string $filename = 'download', string $type = "text/plain") {
        $this->response->setHeader('Content-Description', 'File Transfer');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
        $this->response->setHeader('Pragma', 'public');
        $this->response->setHeader('Cache-Control', 'must-revalidate');
        $this->emit($text, $code, $type);
    }

    /**
     * Emit text without headers
     * @param string $text String text data for emit
     * @param int|null $code Server response code
     */
    public function emitWithoutHeaders(string $text, int|null $code = null) {
        if ($code !== null) {
            $this->response->setResponseCode($code);
        }
        $this->response->setBody($text);
        $this->response->emit();
    }

}
