<?php

declare(strict_types=1);

namespace le7\Core\Controllers\Main;

use le7\Core\User\UserIdentityFactory;
use le7\Core\View\Widget\WidgetFactory;
use le7\Core\Messages\MessageFactory;
use le7\Core\Config\CodePartsFactory;
use le7\Core\DebugPanel\DebugPanel;
use le7\Core\Config\PublicEnvFactory;
use le7\Core\Helpers\UrlHelper;
use le7\Core\GlobalEnvironment;
use le7\Core\Request\Request;
use le7\Core\Response\ResponseWeb;
use le7\Core\Config\TopologyPublicInterface;
use Exception;
use le7\Core\View\Smarty\SmartyConnector;
use Smarty;
use SmartyException;

class WebSmarty extends Web {

    protected Smarty $smarty;

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
            UserIdentityFactory $userIdentityFactory,
            SmartyConnector $smartyConnector
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
                $widgetFactory,
                $userIdentityFactory
                );
        $this->smarty = $smartyConnector->getEngine();
    }

    /**
     * Assign variable in template
     * @param array|string $key
     * @param mixed $value
     * @param bool $nocache
     * @param bool $check
     * @return $this
     */
    public function assign(array|string $key, mixed $value = null, bool $nocache = false, bool $check = true): self {
        if ($check) {
            if (is_string($key)) {
                if (array_key_exists($key, $this->vars)) {
                    trigger_error(_('This variable can not be used in this place:') . ' ' . $key);
                }
            } elseif (is_array($key)) {
                foreach ($key as $keyKey => $keyValue) {
                    if (array_key_exists($keyKey, $this->vars)) {
                        trigger_error(_('This variable can not be used in this place:') . ' ' . $keyKey);
                    }
                }
            }
        }
        $this->smarty->assign($key, $value, $nocache);
        return $this;
    }

    public function setContent(string $templateFile): self {
        $this->vars['content'] = $templateFile;
        return $this;
    }

    /**
     * Render template
     * @param string $layout
     * @param array $components
     */
    public function render(string $layout, array $components = array(), int|null $cacheTimeSec = null): void {
        $this->preRender();
        ob_start();

        // if cached
        if ($cacheTimeSec !== null) {
            $this->tryEmitFromCache();
        }

        $this->assign($this->vars, null, false, false);
        $this->assign($components);
        try {
            $rendered = $this->smarty->fetch($layout);

            // if cache
            $this->tryAddToCache($rendered, $cacheTimeSec);

            $this->response->html->emit($rendered);
        } catch (SmartyException | Exception $e) {
            $this->log->callError($e);
        }
    }

}
