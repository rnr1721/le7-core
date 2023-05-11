<?php

declare(strict_types=1);

namespace Core\DebugPanel\Collectors;

use DebugBar\DataCollector\AssetProvider;
use DebugBar\DataCollector\Renderable;
use DebugBar\DataCollector\DataCollector;
use Core\Interfaces\RouteHttp;

class RouteCollector extends DataCollector implements Renderable, AssetProvider
{

    protected array $data = [];
    protected string $name;
    protected RouteHttp $route;
    protected bool $useHtmlVarDumper = false;

    public function useHtmlVarDumper(bool $value = true): self
    {
        $this->useHtmlVarDumper = $value;
        return $this;
    }

    public function isHtmlVarDumperUsed(): bool
    {
        return $this->useHtmlVarDumper;
    }

    public function __construct(RouteHttp $route, string $name = 'route')
    {
        $this->name = $name;
        $this->route = $route;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function collect(): array
    {

        $route = $this->route;

        $data = array(
            'Method' => $route->getMethod(),
            'Uri' => $route->getUri(),
            'URL params' => $route->getParams(),
            'Language' => $route->getLanguage(),
            'Controller' => $route->getController(),
            'Action' => $route->getAction(),
            'Class' => $route->getControllerClass(),
            'Action method' => $route->getActionMethod(),
            'Proposed response' => $route->getResponse(),
            'Base uri' => $route->getBase(),
            'Type' => $route->getType(),
            'Case' => $route->getCase(),
            'Csrf security' => $route->getCsrf()
        );

        $middleware = $route->getMiddleware();

        if ($this->isHtmlVarDumperUsed()) {
            $data['middleware'] = $this->getVarDumper()->renderVar($middleware);
        } else {
            $data['middleware'] = $this->getDataFormatter()->formatVar($middleware);
        }

        return $data;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAssets(): array
    {
        return $this->isHtmlVarDumperUsed() ? $this->getVarDumper()->getAssets() : array();
    }

    public function getWidgets(): array
    {
        $name = $this->getName();
        $widget = $this->isHtmlVarDumperUsed() ? "PhpDebugBar.Widgets.HtmlVariableListWidget" : "PhpDebugBar.Widgets.VariableListWidget";
        return array(
            "$name" => array(
                "icon" => "gear",
                "widget" => $widget,
                "map" => "$name",
                "default" => "{}"
            )
        );
    }

}
