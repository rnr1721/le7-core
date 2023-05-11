<?php

declare(strict_types=1);

namespace Core\DebugPanel;

use Psr\Log\AbstractLogger;
use Core\Interfaces\Config;
use Core\Interfaces\Request;
use DebugBar\DebugBar;
use DebugBar\DataCollector\DataCollectorInterface;
use DebugBar\JavascriptRenderer;
use Psr\Container\ContainerInterface;
use \RuntimeException;

class DebugPanel
{

    protected bool $canStart = false;
    protected ContainerInterface $container;
    protected Config $config;
    protected Request $request;
    protected ?DebugBar $debugBar = null;
    protected ?JavascriptRenderer $renderer = null;

    public function __construct(ContainerInterface $container, Config $config, Request $request)
    {
        $this->container = $container;
        $this->config = $config;
        $this->request = $request;

        // If DebugBar can start
        $this->canStart = false;
        if (!$this->config->bool('isProduction') && $this->config->bool('debugbar.active')) {
            if (in_array($request->getVisitorIp(), $this->config->array('debugbar.trusted') ?? [])) {
                $this->canStart = true;
            }
        }
        $this->activate();
    }

    private function activate(): bool
    {
        if (!$this->canStart() || !class_exists(DebugBar::class)) {
            return false;
        }
        $this->debugBar = new DebugBar;

        // Get collectors list from config and make it in container
        $collectors = $this->config->array('debugbar.collectors') ?? [];

        foreach ($collectors as $collector) {
            $collectorObject = $this->container->get($collector);
            $this->debugBar->addCollector($collectorObject);
        }

        $this->debugBar["messages"]->addMessage('Welcome to ' . $this->config->string('projectName'));
        $this->debugBar["messages"]->addMessage('You can configure allowed IP addresses to see this debug bar in config/debugbar.php');
        $assetsFolder = $this->config->string('loc.libs') . DIRECTORY_SEPARATOR . 'debugbar';
        if (!file_exists($assetsFolder)) {
            mkdir($assetsFolder, 0775, true);
        }
        $jsFile = $assetsFolder . DIRECTORY_SEPARATOR . 'assets.js';
        $cssFile = $assetsFolder . DIRECTORY_SEPARATOR . 'assets.css';
        $this->renderer = $this->debugBar->getJavascriptRenderer($this->request->getBaseUrl());
        if (!file_exists($jsFile)) {
            $this->renderer->dumpJsAssets($jsFile);
        }
        if (!file_exists($cssFile)) {
            $this->renderer->dumpCssAssets($cssFile);
        }
        return true;
    }

    public function addCollector(DataCollectorInterface $collector): self
    {
        if (!$this->debugBar instanceof DebugBar) {
            throw new RuntimeException("Please create DebugBar instance first");
        }
        $this->debugBar->addCollector($collector);
        return $this;
    }

    public function setMessage(string $message, string $target = "info"): self
    {
        if ($this->canStart()) {

            /** @var AbstractLogger $messages */
            if (!isset($this->debugBar['messages'])) {
                throw new RuntimeException("Messages not available");
            }
            $messages = $this->debugBar["messages"];

            switch ($target) {
                case 'info':
                    $messages->info($message);
                    break;
                case 'error':
                    $messages->error($message);
                    break;
                case 'alert':
                    $messages->alert($message);
                    break;
                case 'warning':
                    $messages->warning($message);
                    break;
                case 'question':
                    $messages->question($message);
                    break;
                default:
                    $messages->info($message);
            }
        }
        return $this;
    }

    public function getJavascriptRenderer(): JavascriptRenderer
    {
        if (!$this->renderer instanceof JavascriptRenderer) {
            throw new RuntimeException("DebugPanel::getJavascriptRenderer no renderer");
        }
        return $this->renderer;
    }

    public function render(): string
    {
        if ($this->renderer instanceof JavascriptRenderer) {
            return $this->renderer->render();
        }
        return '';
    }

    public function canStart(): bool
    {
        return $this->canStart;
    }

}
