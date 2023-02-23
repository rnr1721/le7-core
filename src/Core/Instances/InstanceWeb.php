<?php

declare(strict_types=1);

namespace le7\Core\Instances;

use le7\Core\EventDispatcher\EventInvoker;
use le7\Core\ErrorHandling\ErrorHandlerHttpFactory;
use le7\Core\Php;
use le7\Core\Instances\RouteRunnerHttp;
use le7\Core\ErrorHandling\ErrorLogInterface;
use le7\Core\Config\ConfigInterface;
use le7\Core\Config\TopologyFsInterface;
use Psr\Log\LoggerInterface;
use Psr\Container\ContainerInterface;
use le7\Core\Request\Request;
use le7\Core\Response\Response;
use le7\Core\Locales\LocalesInterface;

class InstanceWeb extends InstanceAbstract implements InstanceInterface {

    protected ErrorHandlerHttpFactory $errorHandlerHttpFactory;
    protected RouteRunnerHttp $routeRunner;
    protected Request $request;
    protected Response $response;

    public function __construct(
            ContainerInterface $container,
            ConfigInterface $config,
            TopologyFsInterface $topology,
            LoggerInterface $systemLog,
            ErrorLogInterface $log,
            Request $request,
            Response $response,
            RouteRunnerHttp $routeRunner,
            EventInvoker $eventInvoker,
            Php $php,
            LocalesInterface $locales,
            ErrorHandlerHttpFactory $errorHandlerHttpFactory
    ) {

        parent::__construct($container, $config, $topology, $systemLog, $log, $eventInvoker, $php, $locales);
        $this->request = $request;
        $this->response = $response;
        $this->routeRunner = $routeRunner;
        $this->errorHandlerHttpFactory = $errorHandlerHttpFactory;
    }

    /**
     * Здесь происходит выбор API или Web
     */
    public function startInstance(RouteInterface $route): RouteRunnerInterface {

        $this->php->setHttpOnly();
        $this->php->setSessionCookieSecure();
        $this->php->setSessionCookieSameSite($this->config->getSessionCookieSamesite());
        $this->php->setSessionPath($this->topology->getPhpSessionsPath());

        session_start();

        $this->locales->setLocale($route->getLanguage());
        $this->errorHandlerHttpFactory->getErrorHandlerHtml();

        $this->response->setResponseCode($route->getResponse());

        $this->eventInvoker->processEvents();

        return $this->routeRunner;
    }

}
