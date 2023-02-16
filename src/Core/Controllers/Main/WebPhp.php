<?php

declare(strict_types=1);

namespace le7\Core\Controllers\Main;

use le7\Core\View\Widget\WidgetFactory;
use le7\Core\Messages\MessageFactory;
use le7\Core\Config\CodePartsFactory;
use le7\Core\Helpers\UrlHelper;
use le7\Core\Config\TopologyPublicInterface;
use le7\Core\Response\ResponseWeb;
use le7\Core\Request\Request;
use le7\Core\GlobalEnvironment;
use le7\Core\Config\PublicEnvFactory;
use le7\Core\DebugPanel\DebugPanel;
use le7\Core\View\Php\PhpViewFactory;
use le7\Core\View\Php\PhpEngine;

class WebPhp extends Web {

    protected PhpEngine $phpView;

    public function __construct(
            GlobalEnvironment $env,
            Request $request,
            ResponseWeb $response,
            TopologyPublicInterface $topologyWeb,
            UrlHelper $urlHelper,
            PublicEnvFactory $publicEnvFactory,
            CodePartsFactory $codePartsFactory,
            DebugPanel $debugbar,
            MessageFactory $messagesFactory,
            WidgetFactory $widgetFactory,
            PhpViewFactory $phpViewFactory
    ) {
        parent::__construct(
                $env,
                $request,
                $response,
                $topologyWeb,
                $urlHelper,
                $publicEnvFactory,
                $codePartsFactory,
                $debugbar,
                $messagesFactory,
                $widgetFactory
                );
        $this->phpView = $phpViewFactory->getPhpView();
    }

    /**
     * Assign variable in template
     * @param array|string|object $key
     * @param string|null $value
     * @return $this
     */
    public function assign(array|string|object $key, string $value = null): self {
        if (is_array($key) || is_object($key)) {
            foreach ($key as $k => $v) {
                $this->vars[$k] = $v;
            }
        } elseif (is_string($key)) {
            $this->vars[$key] = $value;
        }
        return $this;
    }

    /**
     * Render the template
     * @param string $template
     * @param array $data
     */
    public function render(string $template, array $data = array(), int|null $cacheTimeSec = null) {
        $this->preRender();

        // if cached
        if ($cacheTimeSec !== null) {
            $this->tryEmitFromCache();
        }

        $this->assign($data);
        $rendered = $this->phpView->render($template, $this->vars);

        // if cache
        $this->tryAddToCache($rendered, $cacheTimeSec);

        $this->response->html->emit($rendered);
    }

}
