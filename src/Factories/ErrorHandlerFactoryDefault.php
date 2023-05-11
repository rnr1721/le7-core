<?php

declare(strict_types=1);

namespace Core\Factories;

use Core\Interfaces\RouteHttp;
use Core\Interfaces\Config;
use Core\Interfaces\MessageCollection;
use Core\Interfaces\ErrorHandlerFactory;
use Core\ErrorHandler\ErrorHandlerCli;
use Core\ErrorHandler\ErrorHandlerHttp;
use Core\ErrorHandler\Output\ErrorToHtml;
use Core\ErrorHandler\Output\ErrorToJson;
use Core\ErrorHandler\Output\ErrorToCli;
use Core\Console\ColorMessage;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Log\LoggerInterface;
use \Exception;

class ErrorHandlerFactoryDefault implements ErrorHandlerFactory
{

    protected Config $config;
    protected LoggerInterface $logger;
    protected MessageCollection $messageCollection;
    protected ResponseFactoryInterface $responseFactory;

    /**
     * Initializes the class dependencies and sets them as class properties
     * @param Config $config Config manager
     * @param LoggerInterface $logger PSR Logger
     * @param MessageCollection $messageCollection Messages manager
     * @param ResponseFactoryInterface $responseFactory PSR Response factory
     */
    public function __construct(
            Config $config,
            LoggerInterface $logger,
            MessageCollection $messageCollection,
            ResponseFactoryInterface $responseFactory
    )
    {
        $this->config = $config;
        $this->logger = $logger;
        $this->messageCollection = $messageCollection;
        $this->responseFactory = $responseFactory;
    }

    /**
     * Creates and returns an instance of ErrorHandlerCli class
     * @return ErrorHandlerCli
     */
    public function getErrorHandlerCli(): ErrorHandlerCli
    {
        $cm = new ColorMessage();
        $output = new ErrorToCli($this->config, $cm);

        return new ErrorHandlerCli(
                $this->config,
                $this->logger,
                $this->messageCollection,
                $output
        );
    }

    /**
     * Creates and returns an instance of ErrorHandlerHttp class
     * The type of the output is determined by the $route parameter which is an
     * instance of RouteHttp interface
     * @param RouteHttp $route
     * @return ErrorHandlerHttp
     * @throws Exception
     */
    public function getErrorHandlerHttp(RouteHttp $route): ErrorHandlerHttp
    {
        if ($route->getType() === 'web') {
            $output = new ErrorToHtml($this->config, $this->responseFactory);
        } elseif ($route->getType() === 'api') {
            $output = new ErrorToJson($this->responseFactory);
        } else {
            throw new Exception("Route type must be api or web");
        }

        return new ErrorHandlerHttp(
                $this->config,
                $this->logger,
                $this->messageCollection,
                $output
        );
    }

}
