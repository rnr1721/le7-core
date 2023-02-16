<?php

declare(strict_types=1);

namespace le7\Core\Response;

use le7\Core\Response\Response;
use le7\Core\Response\Output\ResponseHtml;
use le7\Core\Response\Output\ResponseJson;
use le7\Core\Response\Output\ResponseText;

class ResponseMain {

    protected Response $response;
    public ResponseHtml $html;
    public ResponseJson $json;
    public ResponseText $text;

    public function __construct(Response $response, ResponseHtml $html, ResponseJson $json, ResponseText $text) {
        $this->response = $response;
        $this->html = $html;
        $this->json = $json;
        $this->text = $text;
    }

    public function getResponse(): ResponseInterface {
        $this->response->responsePsr7;
    }

    /**
     * Set server response code that will be attached to response
     * @param int $code Response code
     * @return self
     */
    public function setResponseCode(int $code) : self {
        $this->response->setResponseCode($code);
        return $this;
    }

    /**
     * Get server response code that will be attached to response
     * @return int
     */
    public function getResponseCode(): int {
        return $this->response->getResponseCode();
    }

    /**
     * Sets caching headers for the response.
     *
     * @param int|string $expires Expiration time
     * @return self Self reference
     */
    public function cache(int|string $expires): self {
        if (empty($expires)) {
            $this->headers['Expires'] = 'Mon, 26 Jul 1997 05:00:00 GMT';
            $this->headers['Cache-Control'] = array(
                'no-store, no-cache, must-revalidate',
                'post-check=0, pre-check=0',
                'max-age=0'
            );
            $this->headers['Pragma'] = 'no-cache';
        } else {
            $expires = is_int($expires) ? $expires : strtotime($expires);
            $this->headers['Expires'] = gmdate('D, d M Y H:i:s', $expires) . ' GMT';
            $this->headers['Cache-Control'] = 'max-age=' . ($expires - time());
            if (isset($this->headers['Pragma']) && $this->headers['Pragma'] == 'no-cache') {
                unset($this->headers['Pragma']);
            }
        }
        return $this;
    }

}
