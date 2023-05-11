<?php

declare(strict_types=1);

namespace Core\Bag;

use Psr\Http\Message\ServerRequestInterface;
use \Exception;

class RequestBag
{

    private ?ServerRequestInterface $request = null;

    /**
     * Get a current ServerRequestInterface from bag
     * @return ServerRequestInterface
     * @throws Exception
     */
    public function getServerRequest(): ServerRequestInterface
    {
        if ($this->request === null) {
            throw new Exception("Before trying get PSR request, you must put it");
        }
        return $this->request;
    }

    /**
     * This method set ServerRequestInterface to bag
     * to get it in future
     * @param ServerRequestInterface $request Fresh PSR ServerRequest
     * @return void
     */
    public function setServerRequest(ServerRequestInterface $request): void
    {
        $this->request = $request;
    }

}
