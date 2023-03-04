<?php

declare(strict_types=1);

namespace App\Core\DebugPanel;

use App\Core\Request\Request;
use App\Core\Config\TopologyFsInterface;
use App\Core\Config\TopologyPublicInterface;
use App\Core\Config\ConfigInterface;
use App\Core\DebugPanel\PhpInfoCollector;
use DebugBar\DataCollector\MessagesCollector;
use DebugBar\DataCollector\RequestDataCollector;
use App\Core\DebugPanel\MemoryCollector;
use DebugBar\DataCollector\ConfigCollector;
use DebugBar\DebugBar;
use DebugBar\JavascriptRenderer;

class DebugPanel {

    private bool|null $canStart = null;
    private ConfigInterface $config;
    private DebugBar $debugbar;
    private JavascriptRenderer $renderer;

    public function __construct(ConfigInterface $config, Request $request, TopologyFsInterface $topologyFs, TopologyPublicInterface $topologyWeb) {
        $this->config = $config;

        $this->canStart = false;
        if (!$this->config->getIsProduction() && $this->config->getDebugBarOn()) {
            if (in_array($request->getVisitorIp(), $this->config->getDebugIps())) {
                $this->canStart = true;
            }
        }

        if ($this->canStart) {

            /** @var DebugBar $debugbar */
            $this->debugbar = new DebugBar;

            $this->debugbar->addCollector(new PhpInfoCollector());
            $this->debugbar->addCollector(new MessagesCollector());
            $this->debugbar->addCollector(new RequestDataCollector());
            $this->debugbar->addCollector(new MemoryCollector());
            $this->debugbar->addCollector(new LocalesCollector());

            $this->debugbar["messages"]->addMessage('Welcome to ' . $this->config->getProjectName());
            $this->debugbar["messages"]->addMessage('You can configure allowed IP addresses to see this debug bar in config.ini');
            $assetsFolder = $topologyFs->getPublicLibsDir() . DIRECTORY_SEPARATOR . 'debugbar';
            if (!file_exists($assetsFolder)) {
                mkdir($assetsFolder, 0775, true);
            }
            $jsFile = $assetsFolder . DIRECTORY_SEPARATOR . 'assets.js';
            $cssFile = $assetsFolder . DIRECTORY_SEPARATOR . 'assets.css';
            $this->renderer = $this->debugbar->getJavascriptRenderer($topologyWeb->getBaseUrl());
            if (!file_exists($jsFile)) {
                $this->renderer->dumpJsAssets($jsFile);
            }
            if (!file_exists($cssFile)) {
                $this->renderer->dumpCssAssets($cssFile);
            }
        }
    }

    public function registerArray(array $array, string $name) : self {
        $this->debugbar->addCollector(new ConfigCollector($array, $name));
        return $this;
    }

    public function registerConfig(array $config): self {
        $this->debugbar->addCollector(new ConfigCollector($config), "Config");
        return $this;
    }

    public function registerResponse(int $code): self {
        $this->debugbar->addCollector(new ResponseCollector($code));
        return $this;
    }

    public function registerDatabase(array $queries): self{
        $this->debugbar->addCollector(new ConfigCollector($queries, "Database"));
        return $this;
    }

    public function setMessage(string $message,string $target = "info"): self {
        if ($this->canStart) {
            switch ($target) {
                case 'info':
                    $this->debugbar["messages"]->info($message);
                    break;
                case 'error':
                    $this->debugbar["messages"]->error($message);
                    break;
                case 'alert':
                    $this->debugbar["messages"]->alert($message);
                    break;
                case 'warning':
                    $this->debugbar["messages"]->warning($message);
                    break;
                case 'question':
                    $this->debugbar["messages"]->question($message);
                    break;
                default:
                    $this->debugbar["messages"]->info($message);
            }
        }
        return $this;
    }

    public function renderBody(): string {
        return $this->renderer->render();
    }

    /**
     * Check if visitor IP is in allowed and if debugbar turned on in config.ini
     * @return bool
     */
    public function canStart(): bool {
        return $this->canStart;
    }

}
