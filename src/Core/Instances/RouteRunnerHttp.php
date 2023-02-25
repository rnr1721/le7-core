<?php

declare(strict_types=1);

namespace le7\Core\Instances;

use le7\Core\Response\Response;
use Psr\Container\ContainerInterface;

class RouteRunnerHttp extends RouteRunner implements RouteRunnerInterface {

    private Response $response;

    public function __construct(ContainerInterface $container, Response $response) {
        parent::__construct($container);
        $this->response = $response;
    }

    public function run(RouteInterface $route): void {

        $controller = $this->getController($route->getControllerClass(), $route);

        $controllerAction = $route->getActionMethod();

        if (method_exists($controller, 'trigger')) {
            $controller->trigger();
        }

        $responseCode = $this->runAction($controller, $route->getActionMethod());

        if ($responseCode === 404) {
            $this->response->setResponseCode($responseCode);
            $notfound = $route->getNotFound();
            if (!empty($notfound)) {
                $controller = $this->getController($notfound['controllerClass'], $route);
                $controllerAction = $notfound['actionMethod'];
                $controller->{$controllerAction}();
            }
        }
        if ($responseCode === 301) {
            $this->response->setResponseCode($responseCode);
        }
        $this->response->emit();
    }

    public function getPublicProperties(string $routeType): array {
        switch ($routeType) {
            case 'web':
                return [
                    'request' => 'le7\Core\Request\Request',
                    'response' => 'le7\Core\Response\ResponseWeb',
                    'topologyWeb' => 'le7\Core\Config\TopologyPublicInterface',
                    'urlHelper' => 'le7\Core\Helpers\UrlHelper',
                    'publicEnvFactory' => 'le7\Core\Config\PublicEnvFactory',
                    'codePartsFactory' => 'le7\Core\Config\CodePartsFactory',
                    'debugbar' => 'le7\Core\DebugPanel\DebugPanel',
                    'messageFactory' => 'le7\Core\Messages\MessageFactory',
                    'userIdentityFactory' => 'le7\Core\User\UserIdentityFactory'
                ];
            case 'api':
                return [
                    'request' => 'le7\Core\Request\Request',
                    'response' => 'le7\Core\Response\ResponseApi',
                    'topologyPublic' => 'le7\Core\Config\TopologyPublicInterface',
                    'userIdentityFactory' => 'le7\Core\User\UserIdentityFactory'
                ];
        }
    }

}
