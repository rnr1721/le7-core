<?php

declare(strict_types=1);

namespace Core\ErrorHandler\Output;

use Core\Interfaces\ConfigInterface;
use Core\Interfaces\ErrorOutputResponseInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use \Throwable;

class ErrorToHtml implements ErrorOutputResponseInterface
{

    protected ConfigInterface $config;
    protected ResponseFactoryInterface $responseFactory;

    public function __construct(
            ConfigInterface $config,
            ResponseFactoryInterface $responseFactory
    )
    {
        $this->config = $config;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param Throwable|null $exception
     * @param array $errors
     */
    public function get(Throwable|null $exception, array $errors): ResponseInterface
    {

        if (ob_get_contents()) {
            ob_clean();
        }
        ob_start();
        $templateDir = $this->config->string('loc.templates_errors');
        $template = $templateDir . DIRECTORY_SEPARATOR . 'View.phtml';
        if (file_exists($template)) {
            include $template;
        }
        $result = ob_get_clean();
        //ob_flush();
        $response = $this->responseFactory->createResponse(503);
        $response->getBody()->write($result);

        $size = $response->getBody()->getSize();

        return $response
                        ->withHeader('Content-Type', 'text/html')
                        ->withHeader('Content-Length', (string) $size);
    }

}
