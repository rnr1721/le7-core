<?php

use App\Core\User\Notifications\Notifications;
use App\Core\User\Notifications\NotificationsInterface;
use App\Core\Locales\TranslateInterface;
use App\Core\Locales\Translate;
use App\Core\Locales\Locales;
use App\Core\Locales\LocalesInterface;
use App\Core\ErrorHandling\ErrorLogInterface;
use App\Core\Helpers\ValidationHelperFactory;
use App\Core\Config\PublicEnvFactory;
//use App\Core\DebugPanel\DebugPanel;
use App\Core\Cache\SCFactoryLe;
use App\Core\Config\ConfigInterface;
use App\Core\Config\TopologyFsInterface;
use App\Core\Config\TopologyPublic;
use App\Core\Config\TopologyPublicInterface;
use App\Core\Config\UserConfig;
use App\Core\Config\UserConfigInterface;
use App\Core\ErrorHandling\ErrorHandlerHttpFactory;
use App\Core\ErrorHandling\ErrorLog;
//use App\Core\EventDispatcher\EventInvoker;
use App\Core\EventDispatcher\Providers\ListenerProvider;
use App\Core\GlobalEnvironment;
//use App\Core\Helpers\UrlHelper;
//use App\Core\Instances\InstanceApi;
//use App\Core\Instances\InstanceCli;
//use App\Core\Instances\InstanceCliData;
//use App\Core\Instances\InstanceHttpData;
//use App\Core\Instances\InstanceWeb;
//use App\Core\Instances\RouteCollection;
//use App\Core\Instances\RouteRunnerCli;
//use App\Core\Instances\RouteRunnerHttp;
use App\Core\Log\LoggerFactory;
use App\Core\Messages\MessageCollection;
use App\Core\Messages\MessageCollectionInterface;
//use App\Core\Php;
//use App\Core\Request\Request;
//use App\Core\Response\Response;
//use App\Core\Response\ResponseWeb;
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
    TranslateInterface::class => autowire(Translate::class),
    NotificationsInterface::class => autowire(Notifications::class)
];
