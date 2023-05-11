<?php

declare(strict_types=1);

namespace Core\Controller\Web;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class NotfoundController
{

    public function indexAction(
            ResponseFactoryInterface $responseFactory
    ): ResponseInterface
    {
        $response = $responseFactory->createResponse(404);
        $response->getBody()->write('Page not found');
        return $response;
    }

}
