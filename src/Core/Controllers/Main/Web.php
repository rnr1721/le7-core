<?php

declare(strict_types=1);

namespace le7\Core\Controllers\Main;

use le7\Core\View\ViewInterface;
use le7\Core\Config\TopologyPublicInterface;
use le7\Core\DebugPanel\DebugPanelRun;
use le7\Core\Instances\RouteHttpInterface;
use le7\Core\Messages\MessageCollectionInterface;
use le7\Core\Messages\MessageFactory;
use le7\Core\Messages\MessageGetInterface;
use le7\Core\Messages\MessagePutInterface;
use le7\Core\Request\Request;
use le7\Core\Response\ResponseWeb;
use le7\Core\View\Php\PageTrait;
use \Exception;

class Web extends Main
{

    use PageTrait;

    protected int|null $cacheLifetime = null;
    protected array $vars = array();
    public MessageFactory $messageFactory;
    private MessageGetInterface $messageGet;
    private MessagePutInterface $messagePut;
    protected MessageCollectionInterface $messagesFlash;
    public ?DebugPanelRun $debugPanelRun = null;
    public Request $request;
    public ResponseWeb $response;
    public RouteHttpInterface $route;
    public TopologyPublicInterface $topologyWeb;
    public ViewInterface $view;

    /**
     * ControllerWeb constructor.
     */
    public function __construct()
    {

        $this->user = $this->request->getAttribute('user');
        $this->route = $this->request->getAttribute('route');

        $this->cacheLifetime = $this->request->getAttribute('cacheLifetime');

        if (!empty($this->messageFactory)) {
            $this->messageGet = $this->messageFactory->getGetStorage();
            $this->messagePut = $this->messageFactory->getPutStorage();
            $this->messagesFlash = $this->messageFactory->newInstance();
        }
    }

    public function preRender()
    {

        $this->handleFlashMessages();

        $vars = array(
            'styles' => '',
            'title' => '',
            'header' => '',
            'importmap' => '',
            'scripts_header' => '',
            'scripts_footer' => '',
            'microformat' => '',
            'keywords' => '',
            'description' => '',
            'content' => '',
            'user' => $this->user
        );

        // From middleware
        $webpage = $this->request->getAttribute('webpage', null);
        if ($webpage) {
            foreach ($webpage as $key => $value) {
                $vars[$key] = $value;
            }
        }

        if (!empty($this->messageFactory)) {
            $vars['messages'] = $this->messages->getAll();
        }

        foreach ($vars as $cKey => $cValue) {
            if (!isset($this->vars[$cKey])) {
                $this->vars[$cKey] = $cValue;
            }
        }

        if ($this->debugPanelRun) {
            $this->setScriptLib('debugbar/assets.js');
            $this->setStyleLib('debugbar/assets.css');
            $this->vars['scripts_footer'] .= $this->debugPanelRun->render();
        }
    }

    public function indexGetAjax()
    {
        return $this->response->json->emitError(404);
    }

    public function indexPostAjax()
    {
        return $this->response->json->emitError(404);
    }

    public function indexPutAjax()
    {
        return $this->response->json->emitError(404);
    }

    public function indexDeleteAjax()
    {
        return $this->response->json->emitError(404);
    }

    public function indexPatchAjax()
    {
        return $this->response->json->emitError(404);
    }

    public function tryAddToCache(string $rendered, int|null $cacheTimeSec = null)
    {
        if ($cacheTimeSec !== null && !empty($this->cache)) {
            $routeType = $this->route->getType();
            $currentUri = $this->request->getUri();
            $cacheName = $routeType . '_' . md5((string) $currentUri);
            if ($cacheTimeSec === 0) {
                $this->cache->set($cacheName, $rendered);
            } else {
                $this->cache->set($cacheName, $rendered, $cacheTimeSec);
            }
        }
    }

    public function setContent($contentTemplate)
    {
        $this->vars['content'] = $contentTemplate;
    }

    protected function handleFlashMessages()
    {
        // Flash messages in session or cookies (depend config params)
        if (!empty($this->messageFactory)) {
            $this->messages->loadMessages($this->messageGet);
            $this->messagesFlash->putMessages($this->messagePut);
        }
    }

    public function assign(array|string|object $key, string $value = null, bool $noCache = true, bool $check = true): self
    {
        if (is_array($key) || is_object($key)) {
            foreach ($key as $k => $v) {
                if ($check && array_key_exists($k, $this->vars)) {
                    throw new Exception(_('This variable can not be used in this place:') . ' ' . $k);
                }
                $this->vars[$k] = $v;
            }
        } elseif (is_string($key)) {
            if ($check && array_key_exists($key, $this->vars)) {
                throw new Exception(_('This variable can not be used in this place:') . ' ' . $key);
            }
            $this->vars[$key] = $value;
        }
        return $this;
    }

    public function render(string $template, array $data = array(), int|null $cacheTimeSec = null)
    {
        $this->preRender();

        $this->assign($data);
        $rendered = $this->view->render($template, $this->vars);

        // if cache
        $this->tryAddToCache($rendered, $cacheTimeSec);

        $this->response->html->emit($rendered);
    }

}
