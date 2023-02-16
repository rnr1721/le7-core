<?php

namespace le7\Core\Response\Output;

class ResponseHtml extends ResponseOutput {

    public function __invoke(string $html, int|null $code = null): void {
        $this->emit($html, $code);
    }

    /**
     * Output HTML data with security headers
     * You can configure security headers in config.ini
     * @param string $html Html string to output
     * @param int|null $code Server response code
     * @return void
     */
    public function emit(string $html, int|null $code = null): void {
        $this->response->
                setHeader('Strict-Transport-Security', $this->config->getHeaderStrictTransportSecurity())
                ->setHeader('Content-Security-Policy', $this->config->getHeaderContentSecurityPolicy())
                ->setHeader('Referrer-Policy', $this->config->getHeaderReferrerPolicy())
                ->setHeader('X-Content-Type-Options', $this->config->getHeaderXcontentTypeOptions())
                ->setHeader('X-Frame-Options', $this->config->getHeaderXframeOptions())
                ->setHeader('X-XSS-Protection', $this->config->getHeaderXxssProtection());
        $this->emitWithoutHeaders($html, $code);
    }

    /**
     * Output HTML data without security headers
     * Only with Content-Type
     * @param string $html Html string to output
     * @param int|null $code Server response code
     */
    public function emitWithoutHeaders(string $html, int|null $code = null) {
        if ($code !== null) {
            $this->response->setResponseCode($code);
        }
        $this->response->setHeader('Content-Type', "text/html");
        $this->response->setBody($html);
        $this->response->emit();
    }

}
