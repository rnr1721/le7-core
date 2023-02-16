<?php

declare(strict_types=1);

namespace le7\Core\Instances;

use le7\Core\Php;
use le7\Core\Config\ConfigInterface;
use le7\Core\Config\TopologyFsInterface;
use le7\Core\EventDispatcher\EventInvoker;
use le7\Core\Request\Request;
use le7\Core\Response\Response;
use le7\Core\Locales\Locales;
use le7\Core\ErrorHandling\ErrorHandlerHttpFactory;
use le7\Core\ErrorHandling\ErrorLog;
use Psr\Log\LoggerInterface;
use Psr\Container\ContainerInterface;

class InstanceApi extends InstanceAbstract implements InstanceInterface {

    protected ErrorHandlerHttpFactory $errorHandlerHttpFactory;
    protected RouteRunnerHttp $routeRunner;
    protected Request $request;
    protected Response $response;

    public function __construct(
            ContainerInterface $container,
            ConfigInterface $config,
            TopologyFsInterface $topology,
            LoggerInterface $systemLog,
            ErrorLog $log,
            Request $request,
            Response $response,
            RouteRunnerHttp $routeRunner,
            EventInvoker $eventInvoker,
            Php $php,
            Locales $locales,
            ErrorHandlerHttpFactory $errorHandlerHttpFactory
    ) {

        parent::__construct($container, $config, $topology, $systemLog, $log, $eventInvoker, $php, $locales);
        $this->request = $request;
        $this->response = $response;
        $this->routeRunner = $routeRunner;
        $this->errorHandlerHttpFactory = $errorHandlerHttpFactory;
    }

    public function startInstance(RouteInterface $route): RouteRunnerInterface {

        if (getenv('REQUEST_METHOD') === 'OPTIONS') {
            $this->setApiHeaders();
            $this->response->emit();
        }

        $this->setApiHeaders();

        $this->locales->setLocale($route->getLanguage());
        $this->errorHandlerHttpFactory->getErrorHandlerJson();

        $this->response->setResponseCode($route->getResponse());

        $this->eventInvoker->processEvents();

        return $this->routeRunner;
    }

    /**
     * Устанавливает заголовки для API
     */
    public function setApiHeaders(): void {
        $this->response->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Methods', $this->config->getApiAllowedMethods())
                ->setHeader('Access-Control-Allow-Credentials', 'true')
                ->setHeader('Access-Control-Allow-Headers', $this->config->getApiAllowedHeaders());
    }

}
