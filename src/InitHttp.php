<?php

declare(strict_types=1);

namespace Core;

use Core\Interfaces\BundleManagerInterface;
use Core\Interfaces\ResponseEmitterInterface;
use Core\Interfaces\MiddlewareFactoryInterface;
use Core\Interfaces\LocalesInterface;
use Core\Bag\RouteBag;
use Core\Bag\RequestBag;
use Core\ErrorHandler\ErrorHandlerHttp;
use Core\Routing\RouteBuilderHttp;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use \Exception;
use \Throwable;

/**
 * Class for handle HTTP requests - web and api
 */
class InitHttp implements RequestHandlerInterface
{

    use InitTrait;

    /**
     * This method can emit response (ResponseInterface)
     * You can use it with apache or nginx and PHP-FPM
     * @param ServerRequestInterface $request
     * @return void
     */
    public function run(ServerRequestInterface $request): void
    {
        $container = $this->getContainer();
        $response = $this->handle($request);
        /** @var ResponseEmitterInterface $responseEmitter */
        $responseEmitter = $container->get(ResponseEmitterInterface::class);
        $responseEmitter->emit($response);
    }

    /**
     * This is request handler
     * You can use it with workerman or similar servers
     * it give ServerRequestInterface, handle it and return ResponseInterface
     * @param ServerRequestInterface $request Request for handle
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $container = $this->getContainer();
        /** @var RequestBag $requestBag */
        $requestBag = $container->get(RequestBag::class);
        $requestBag->setServerRequest($request);
        /** @var RouteBag $routeBag */
        $routeBag = $container->get(RouteBag::class);
        $this->initBundles();
        /** @var RouteBuilderHttp $routeBuilder */
        $routeBuilder = $container->get(RouteBuilderHttp::class);
        $route = $routeBuilder->getCurrentRoute();
        $routeBag->setRoute($route);
        /** @var ErrorHandlerHttp $errorHandler */
        $errorHandler = $container->get(ErrorHandlerHttp::class);
        try {
            $locales = $container->get(LocalesInterface::class);
            $locales->setLocale($route->getLanguage());
            /** @var MiddlewareFactoryInterface $middlewareFactory */
            $middlewareFactory = $container->get(MiddlewareFactoryInterface::class);
            $middlewares = $middlewareFactory->getMiddleware($route);

            /** @var ResponseInterface $response */
            $response = $middlewares->handle($request);
        } catch (Throwable $e) {
            $errorHandler->handleException($e);
            $response = $errorHandler->getResponse();
        }

        // If other errors
        if ($errorHandler->isErrors()) {
            $response = $errorHandler->getResponse();
        }

        return $response;
    }

    public function initBundles(): void
    {
        $container = $this->getContainer();
        /** @var BundleManagerInterface $bundleManager */
        $bundleManager = $container->get(BundleManagerInterface::class);
        if ($this->config === null) {
            throw new Exception('ConfigInterface not found');
        }
        $bundles = $this->config->array('bundles.list') ?? [];
        $bundleManager->addBundles($bundles);
        $bundleManager->initAll();
    }

}
