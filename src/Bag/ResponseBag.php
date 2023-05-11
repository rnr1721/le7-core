<?php

declare(strict_types=1);

namespace Core\Bag;

use Psr\Http\Message\ResponseInterface;
use \Exception;

class ResponseBag
{

    /**
     * Current PSR response
     * @var ResponseInterface|null
     */
    private ?ResponseInterface $response = null;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * Get current PSR response from bag
     * @return ResponseInterface
     * @throws Exception
     */
    public function getResponse(): ResponseInterface
    {
        if ($this->response === null) {
            throw new Exception("Before trying get PSR response, you must put it");
        }
        return $this->response;
    }

    /**
     * Set current PSR response to bag
     * @param ResponseInterface $response Current PSR response
     * @return ResponseInterface|null
     */
    public function setResponse(ResponseInterface $response): ResponseInterface|null
    {
        $oldResponse = $this->response;
        $this->response = $response;
        return $oldResponse;
    }

}
