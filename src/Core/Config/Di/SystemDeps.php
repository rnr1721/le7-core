<?php

use le7\Core\Locales\TranslateInterface;
use le7\Core\Locales\Translate;
use le7\Core\Locales\Locales;
use le7\Core\Locales\LocalesInterface;
use le7\Core\ErrorHandling\ErrorLogInterface;
use le7\Core\Helpers\ValidationHelperFactory;
use le7\Core\Config\PublicEnvFactory;
//use le7\Core\DebugPanel\DebugPanel;
use le7\Core\Cache\SCFactoryLe;
use le7\Core\Config\ConfigInterface;
use le7\Core\Config\TopologyFsInterface;
use le7\Core\Config\TopologyPublic;
use le7\Core\Config\TopologyPublicInterface;
use le7\Core\Config\UserConfig;
use le7\Core\Config\UserConfigInterface;
use le7\Core\ErrorHandling\ErrorHandlerHttpFactory;
use le7\Core\ErrorHandling\ErrorLog;
//use le7\Core\EventDispatcher\EventInvoker;
use le7\Core\EventDispatcher\Providers\ListenerProvider;
use le7\Core\GlobalEnvironment;
//use le7\Core\Helpers\UrlHelper;
//use le7\Core\Instances\InstanceApi;
//use le7\Core\Instances\InstanceCli;
//use le7\Core\Instances\InstanceCliData;
//use le7\Core\Instances\InstanceHttpData;
//use le7\Core\Instances\InstanceWeb;
//use le7\Core\Instances\RouteCollection;
//use le7\Core\Instances\RouteRunnerCli;
//use le7\Core\Instances\RouteRunnerHttp;
use le7\Core\Log\LoggerFactory;
use le7\Core\Messages\MessageCollection;
use le7\Core\Messages\MessageCollectionInterface;
//use le7\Core\Php;
//use le7\Core\Request\Request;
//use le7\Core\Response\Response;
//use le7\Core\Response\ResponseWeb;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use function DI\factory;
use function DI\get;
use function DI\autowire;

/** @var ConfigInterface $config */
global $config;
/** @var TopologyFsInterface $topology */
global $topology;

return [
    ConfigInterface::class => $config,
    TopologyFsInterface::class => $topology,
    TopologyPublicInterface::class => autowire(TopologyPublic::class),
    //Php::class => autowire(Php::class),
    //Psr17Factory::class => autowire(Psr17Factory::class),
    ServerRequestInterface::class => factory(function (ContainerInterface $c) {
        $creator = new ServerRequestCreator(
                $c->get(Psr17Factory::class), // ServerRequestFactory
                $c->get(Psr17Factory::class), // UriFactory
                $c->get(Psr17Factory::class), // UploadedFileFactory
                $c->get(Psr17Factory::class)  // StreamFactory
        );
        return $creator->fromGlobals();
    }),
    //Request::class => autowire(Request::class),
    ResponseFactoryInterface::class => get(Psr17Factory::class),
    //InstanceHttpData::class => autowire(InstanceHttpData::class),
    //InstanceCliData::class => autowire(InstanceCliData::class),
    //RouteCollection::class => autowire(RouteCollection::class),
    //SCFactoryLe::class => autowire(SCFactoryLe::class),
    CacheInterface::class => factory(function (ContainerInterface $c) {
        /** @var SCFactoryLe $cf */
        $cf = $c->get(SCFactoryLe::class);
        /** @var ConfigInterface $config */
        $config = $c->get(ConfigInterface::class);
        return match ($config->getDefaultCacheMethod()) {
            'file' => $cf->getFileCache(),
            'memcache' => $cf->getMemcache(),
            'memcached' => $cf->getMemcached()
        };
    }),
    LoggerInterface::class => factory(function (ContainerInterface $c) {
        $f = $c->get(LoggerFactory::class);
        /** @var LoggerFactory $f */
        return $f->getSystemLogger();
    }),
    ErrorLogInterface::class => autowire(ErrorLog::class),
    //LoggerFactory::class => autowire(LoggerFactory::class),
    //InstanceWeb::class => autowire(InstanceWeb::class),
    //InstanceApi::class => autowire(InstanceApi::class),
    //InstanceCli::class => autowire(InstanceCli::class),
    ErrorHandlerHttpFactory::class => autowire(ErrorHandlerHttpFactory::class),
    MessageCollectionInterface::class => autowire(MessageCollection::class),
    //EventInvoker::class => autowire(EventInvoker::class),
    ListenerProviderInterface::class => autowire(ListenerProvider::class),
    GlobalEnvironment::class => autowire(GlobalEnvironment::class),
    UserConfigInterface::class => autowire(UserConfig::class),
    //Response::class => autowire(Response::class),
    //ResponseWeb::class => autowire(ResponseWeb::class),
    //UrlHelper::class => autowire(UrlHelper::class),
    PublicEnvFactory::class => autowire(PublicEnvFactory::class),
    //RouteRunnerCli::class => autowire(RouteRunnerCli::class),
    //RouteRunnerHttp::class => autowire(RouteRunnerHttp::class),
    //DebugPanel::class => autowire()
    ValidationHelperFactory::class => autowire(ValidationHelperFactory::class),
    LocalesInterface::class => autowire(Locales::class),
    TranslateInterface::class => autowire(Translate::class)
];
