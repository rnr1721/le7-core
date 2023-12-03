# Config directory with PHP config files and DependencyInjection

This directory contain config files. It can be in PHP (with return array), INI
or Json formats. System know it by extension. When you launch engine, LE7
harvest them from folder and load as one configuration. The arrays in these files
must contain only unique keys.

- **config.php** - base configuration.
- **debugbar.php** - configuration of debug panel
- **events.php** - Event Manager configuration - link between events and listeners
- **headers.php** - default headers for web and api routes
- **locales.php** - configure locales settings
- **middleware.php** - configure system Middleware (MiddlewareInterface)
- **routes.php** - routing settings
- **user.php** - file for user configuration.

Of course, you can create own config files and it will be automatically
handled by engine. Keys must be unique!

## How use config in system?

You can use config for example in controllers, middlewares, events or classes

For example in controller using DI
For more information about config component please see
https://github.com/rnr1721/le7-config

```php
<?php

namespace App\Controller\Web;

use Core\Interfaces\ConfigInterface;
use Psr\Http\Message\ResponseInterface;
use App\Controller\ControllerWeb;

class IndexController extends ControllerWeb
{

    public function indexAction(ConfigInterface $config): ResponseInterface
    {

        $projectName = $config->string('projectName');
        // If config key not exists, it return default
        $timeZone = $config->string('timeZone','Europe/Moscow');
        $theme = $config->string('theme');
        // You can access config arrays by path
        $middlewareList = $config->array('middleware.web');
        
        return $this->view->render('layout.twig', [
            'projectName' => $projectName
        ]);
    }

}
```
## config.php

### scriptVersion

Version of product. Can be used for example when we want add script version to
web page. This is not le7 version! This is product (your script) version

### projectName

Your project name for use in project and orher places

### publicSubdir

If you using apache or nginx web servers, and your script not in public folder.
For example, your script placed in {WEB_PUBLIC_DIR}/myscript
publicSubdir value must be "mydir". Of cource in this case you need to edit
index.php in project web public dir.

### defaultController

This is default controller that le7 will find in controller not specified.
For example we want to get address https://example.com . In this case we not
specify controller or action.
By default defaultController parameter is "index", so your root controller
class name will be IndexController

### defaultAction

This is default action name. Every le7 controller class have methods - actions.
So default action may look like this (dependent of request type):

- **indexAction** - for GET requests
- **indexPostAction** - for POST requests
- **indexPutAction** - for Put requests
- **indexDeleteAction** - for Delete requests
- **indexAjax** - for XML http requests

### notfoundController

Default value is "notfound". This is notfound controller name if action not
exists. So if default value of this parameter is "notfound", nothound controller
will be "NotfoundController". Default action of notfound controller will be
equal to defaultAction parameter value.

### notfoundWebNamespace

Namespace for web routes where le7 will find Notfound controller. If it empty,
le7 will find notfound controller in route PHP namespace.
Default: App\Controller\Web

If empty you will can have own notfound controller for every route group

### notfoundApiNamespace

Namespace for api routes where le7 will find Notfound controller. If it empty,
le7 will find notfound controller in route PHP namespace.
Default: App\Controller\Api\v1

If empty you will can have own notfound controller for every route group

### commandNamespace

Namespace for command line controllers. Cannot be empty.
Default: App\Controller\Cli

### timezone

Timezone in standard format. Cannot be empty

### theme

Name of theme folder. Themes places in {WEBROOT}/themes/{THEME_NAME}
Default: main

### errorReporting

Boolean value. Set PHP error_reporting option

### viewExtensions

folder for extensions of Twig, Smarty or PHP template engines
Be default this parameter value contain varisbles as '{ds}', '{base}' etc.
To get it as path you can use stringf method of Config component

```php
$extensionPath = $config->stringf('viewExtensions');
```

## debugbar.php

### debugbar

This array contain parameters of PHP DebugBar.
DebugBar automatically off when turn on production mode.

#### active

Can be true or false. You can use or not the DebugBar panel for web routes.

#### collectors

This array contain list of collectors. Feel free to add own collectors.
To write own collectors - please read phpDebugBar documentation:
http://phpdebugbar.com/

#### trusted

This string parameter mean trusted hosts to run PHP DebugBar. You can set one
or more comma-separated IPs.
Default: "127.0.0.1"

## events.php

### events

In this file you can link events with listeners.
Elements of "Events" array - arrays with pairs of event and listener class.
Default content is:

```php
<?php

use Core\Events\BeforeRenderEvent;
use Core\EventListeners\DebugBarListener;

return [
    'events' => [
        [
            BeforeRenderEvent::class,
            DebugBarListener::class
        ]
    ]
];

```

## headers.php

### headers

This array can content arrays with headers for different types of routes
Format key=>value
Feel free for modify it and/or add own headers
Also, You can add own headers in middleware.

#### web

Headers that automatically show in web routes
Middleware for add this headers: Core\Middleware\WebHeadersMiddleware

#### api

Headers that automatically show in web routes
Middleware for add this headers: Core\Middleware\ApiHeadersMiddleware

## locales.php

This parameter set up locales. If you add own locales, it will automatically
added to all multilingual routes (see "routes" section in this documentation).

Default locales.php

```php
<?php

return [
    'defaultLanguage' => 'en',
    'locales' => [
        'ru' => [
            'name' => 'ru_RU',
            'label' => 'Русский'
        ],
        'en' => [
            'name' => 'en_US',
            'label' => 'English'
        ],
        'ua' => [
            'name' => 'uk_UA',
            'label' => 'Українська'
        ]
    ]
];

```

### defaultLanguage

Default language. It must be one of 'locales' items

### locales

locales parameter is the array, that contain arrays with available languages.
For each language very well to have .po and .mo files in ./App/Locales folder.
Every locales must have folder with locale name with this directory structure:

```
  Locales
    ru_RU
        LC_MESSAGES
            le7_ru_RU.mo
            le7_ru_RU.po
```

## middleware.php

This config file contains the middleware that queue while generating response.
Feel free to add your own middleware.
Read more: https://www.php-fig.org/psr/psr-15/

### runner

Default middleware for run actions of controller.
Default: Core\Middleware\ControllerRunMiddleware

### middleware

This is array with list of middleware queue.
Every middleware must be implementation of MiddlewareInterface

#### web

Middleware queue for web routes.

#### api

Middleware queue for api routes.

Example of PSR middleware with DI injected route:

```php
<?php

declare(strict_types=1);

namespace App\Middleware;

use Core\Interfaces\RouteHttpInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Example implements MiddlewareInterface
{

    public RouteHttpInterface $route;


    public function __construct(RouteHttpInterface $route)
    {
        $this->route = $route;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        
        return $response;
    }

}
```

Example of clean middleware:

```php
<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Example implements MiddlewareInterface
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        return $response;
    }

}
```

## routes.php

### routes

This file contain routes option with configuration of routing.

Standard route:

- https://example.com/{language}{route}{controller}{action}
- https://example.com/ru/dashboard/users/list

in this example:

- **ru** - language. If is default language it will be empty.
- **dashboard** - route group. Can be empty if it default route group
- **users** - controller. Can be empty. If empty - le7 will search index controller
- **list** - action. it is a method of controller class

Another example for default route group (with empty route group):

- https://example.com/{language}{controller}{action}
- https://example.com/ru/page/contacts

in this example

- **ru** - language. Can be empty if default language (https://example.com/page/contacts)
- **page** - controller
- **contacts** - action

Routes can be of two types: web and api.
Typicall route have these required parameters:

#### key (string)

This is unique value for route. Must contain any latin characters.
You can give any names for routes, but it must have only latin chars
without any other symbols, whitespases and other data.

#### type (string)

This is type of route. Allowed types "web" or "api".
Technically is very similar, but have differences - different default
headers, different middleware queues, etc.

#### address (string)

Address of route. For example, "/admin" or "/dashboard" or "/customer/retail"

#### namespace (string)

PHP namespace where le7 will find controllers. This namespace for route group

#### params (int)

Number of params after URL.

#### multilang (bool)

Multi-language option. if it false, system create one route. If true, system
will create route for every language and you will can get route on every language
that you set in config

### Default contenrs of routes.php

Default contents:

```php
<?php

return array(
    'routes' => [
        'admin' => [
            'key' => 'admin',
            'type' => 'web',
            'address' => '/admin',
            'namespace' => 'App\Controller\Web\Admin',
            'params' => 7,
            'multilang' => true
        ],
        'apiv1' => [
            'key' => 'apiv1',
            'type' => 'api',
            'address' => '/api/v1',
            'namespace' => 'App\Controller\Api\v1',
            'params' => 7,
            'multilang' => false
        ],
        'web' => [
            'key' => 'web',
            'type' => 'web',
            'address' => '/',
            'namespace' => 'App\Controller\Web',
            'params' => 7,
            'multilang' => true
        ]
    ]
);
```
