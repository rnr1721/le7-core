<?php

declare(strict_types=1);

namespace Core\Routing;

use Core\Bag\RouteBag;
use Core\Console\CliOptions;
use Core\Interfaces\Config;
use Core\Interfaces\RouteCli;

class RouteBuilderCli
{

    private RouteBag $routeBag;
    private CliOptions $cliOptions;
    private Config $config;
    public string $defController;
    public string $defAction;
    public string $defLanguage;
    public string $commandNamespace;
    private string $indexController = '\Core\Controller\Console\IndexController';
    private string $notfoundController = '\Core\Controller\Console\Notfound';
    private string $systemNamespace = '\Core\Controller\Console\System\\';

    public function __construct(Config $config, CliOptions $cliOptions, RouteBag $routeBag)
    {
        $this->routeBag = $routeBag;
        $this->cliOptions = $cliOptions;
        $this->config = $config;
        $this->defController = 'index';
        $this->defAction = 'index';
        $this->defLanguage = $config->string('defaultLanguage') ?? 'en';
        $this->commandNamespace = $config->string('commandNamespace') ?? "App\Controller\Cli";
    }

    /**
     * Get current CLI route as object
     * @return RouteCli
     */
    public function getCurrentRoute(): RouteCli
    {

        $params = $this->cliOptions->getOptions();

        $defCa = $this->defController . ':' . $this->defAction;
        $caString = $this->cliOptions->getByNumberNotOption(0, $defCa);

        $ca = explode(':', $caString);

        $controller = (isset($ca[0]) ? $ca[0] : $this->defController);
        $action = (isset($ca[1]) ? $ca[1] : $this->defAction);

        $language = $this->cliOptions->getOption('l', $this->defLanguage);
        if (!array_key_exists($language, $this->config->array('locales') ?? [])) {
            $language = $this->defLanguage;
        }

        $pController = $this->getControllerClass($controller);

        $pAction = $this->getAction($action);

        $settings = [];

        $settings['help'] = $this->cliOptions->optionExists('help');

        $settings['silent'] = $this->cliOptions->optionExists('silent');

        if ($pController === null) {
            return new RouteCliGeneric($this->getNotFound($language, $settings));
        }

        /** @var class-string $pController */
        if (!method_exists($pController, $pAction)) {
            return new RouteCliGeneric($this->getNotFound($language, $settings));
        }

        $routeData = array(
            'type' => 'cli',
            'case' => 'cli',
            'language' => $language,
            'controller' => $controller,
            'action' => $action,
            'controllerClass' => $pController,
            'actionMethod' => $pAction,
            'params' => $params,
            'options' => $settings
        );

        $route = new RouteCliGeneric($routeData);

        $this->routeBag->setRoute($route);

        return $route;
    }

    /**
     * Get notfound CLI route data
     * @param string $language Language
     * @param array $settings Options
     * @return array
     */
    private function getNotFound(string $language, array $settings): array
    {
        return array(
            'type' => 'cli',
            'case' => 'cli',
            'language' => $language,
            'controller' => 'notfound',
            'action' => 'index',
            'controllerClass' => $this->notfoundController,
            'actionMethod' => $this->getAction($this->defAction),
            'params' => array(),
            'options' => $settings
        );
    }

    /**
     * Get name of action method
     * @param string $action
     * @return string
     */
    public function getAction(string $action): string
    {
        return $action . 'Action';
    }

    /**
     * Get funn qualified controller class name or null if not found
     * At first, it find it in core dir (system commands), at second - 
     * in app project dir
     * @param string|null $controller
     * @return string|null
     */
    protected function getControllerClass(?string $controller = null): string|null
    {
        if ($controller === null) {
            return $this->indexController;
        }
        $classSystem = $this->systemNamespace . ucfirst($controller) . 'Controller';
        $classUser = '\\' . $this->commandNamespace . '\\' . ucfirst($controller) . 'Controller';
        if (class_exists($classSystem)) {
            return $classSystem;
        }
        if (class_exists($classUser)) {
            return $classUser;
        }
        if ($controller === $this->defController) {
            return $this->indexController;
        }
        return null;
    }

}
