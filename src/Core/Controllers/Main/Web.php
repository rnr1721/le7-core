<?php

declare(strict_types=1);

namespace App\Core\Controllers\Main;

use App\Core\View\ViewInterface;
use App\Core\Config\TopologyPublicInterface;
use App\Core\DebugPanel\DebugPanelRun;
use App\Core\Instances\RouteHttpInterface;
use App\Core\Messages\MessageCollectionInterface;
use App\Core\Messages\MessageFactory;
use App\Core\Messages\MessageGetInterface;
use App\Core\Messages\MessagePutInterface;
use App\Core\Request\Request;
use App\Core\Response\ResponseWeb;
use App\Core\View\Php\PageTrait;
use \Exception;

/**
 * Default controller for web requests
 * All web controllers must extends from it
 */
class Web extends Main
{

    /**
     * Trait that set page title, set scripts or styles etc
     */
    use PageTrait;

    /**
     * Cache lifetime in seconds
     * Can be null, 0 or int. 0 is permanent cache, null turn off cache
     * @var int|null
     */
    protected int|null $cacheLifetime = null;
    
    /**
     * Webpage vars
     * @var array
     */
    protected array $vars = [];
    
    /**
     * Factory for handling flash messages
     * @var MessageFactory
     */
    public MessageFactory $messageFactory;
    
    /**
     * System object for handling flash messages
     * Dont use it directly
     * @var MessageGetInterface
     */
    private MessageGetInterface $messageGet;
    
    /**
     * System object for handling flash messages
     * Dont use it directly
     * @var MessagePutInterface
     */
    private MessagePutInterface $messagePut;
    
    /**
     * With this you can send flash messages stored with cookies or session
     * @var MessageCollectionInterface
     */
    protected MessageCollectionInterface $messagesFlash;
    
    /**
     * DebugBar - it call if it can start
     * @var DebugPanelRun|null
     */
    public ?DebugPanelRun $debugPanelRun = null;
    
    /**
     * System Request object
     * @var Request
     */
    public Request $request;
    
    /**
     * System response object
     * @var ResponseWeb
     */
    public ResponseWeb $response;
    
    /**
     * Current route
     * @var RouteHttpInterface
     */
    public RouteHttpInterface $route;
    
    /**
     * Object to get links for base URL, theme URL, JS URL etc
     * @var TopologyPublicInterface
     */
    public TopologyPublicInterface $topologyWeb;
    
    /**
     * Current view engine that can render templates -
     * Clean PHP, Smarty, Twig etc
     * @var ViewInterface|null
     */
    public ?ViewInterface $view = null;

    /**
     * Web constructor
     */
    public function __construct()
    {

        $this->user = $this->request->getAttribute('user');
        $this->vars['user'] = $this->user;
        $this->route = $this->request->getAttribute('route');

        $this->cacheLifetime = $this->request->getAttribute('cacheLifetime');

        if (!empty($this->messageFactory)) {
            $this->messageGet = $this->messageFactory->getGetStorage();
            $this->messagePut = $this->messageFactory->getPutStorage();
            $this->messagesFlash = $this->messageFactory->newInstance();
        }
    }

    /**
     * Method that calls befor render page
     */
    public function preRender()
    {

        $this->handleFlashMessages();

        $vars = array(
            'title' => '',
            'header' => '',
            'importmap' => '',
            'styles' => '',
            'scripts_header' => '',
            'scripts_footer' => '',
            'microformat' => '',
            'keywords' => '',
            'description' => '',
            'content' => '',
            'user' => null
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

        if ($this->debugPanelRun && $this->debugPanelRun->canStart()) {
            $this->setScriptLib('debugbar/assets.js');
            $this->setStyleLib('debugbar/assets.css');
            $this->vars['scripts_footer'] .= $this->debugPanelRun->render();
        }
    }

    /**
     * 404 default page for GET AJAX requests
     * @return type
     */
    public function indexGetAjax()
    {
        return $this->response->json->emitError(404);
    }

    /**
     * 404 default page for POST AJAX requests
     * @return type
     */
    public function indexPostAjax()
    {
        return $this->response->json->emitError(404);
    }
    
    /**
     * 404 default page for PUT AJAX requests
     * @return type
     */
    public function indexPutAjax()
    {
        return $this->response->json->emitError(404);
    }

    /**
     * 404 default page for DELETE AJAX requests
     * @return type
     */
    public function indexDeleteAjax()
    {
        return $this->response->json->emitError(404);
    }
    
    /**
     * 404 default page for PATCH AJAX requests
     * @return type
     */
    public function indexPatchAjax()
    {
        return $this->response->json->emitError(404);
    }

    /**
     * Set content template to include it in layout
     * @param type $contentTemplate
     */
    public function setContentTemplate($contentTemplate)
    {
        $this->vars['content'] = $contentTemplate;
    }

    /**
     * Get flash messages from session or cookies
     * and put in into system messages
     */
    protected function handleFlashMessages()
    {
        // Flash messages in session or cookies (depend config params)
        if (!empty($this->messageFactory)) {
            $this->messages->loadMessages($this->messageGet);
            $this->messagesFlash->putMessages($this->messagePut);
        }
    }

    /**
     * Assign variable to template
     * @param array|string|object $key Key as string or array $key=>$value
     * @param mixed $value Value of assigned element
     * @param bool $check Check if var defined in page variables
     * @return self
     * @throws Exception
     */
    public function assign(array|string|object $key, mixed $value = null, bool $check = true): self
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

    /**
     * Render web page using ViewInterface if it injected
     * @param string $template Template file with extension
     * @param array $data Data for using in template
     * @param int|null $cacheTimeSec null - no cache, 0 - permanent cache or int
     */
    public function render(string $template, array $data = array(), int|null $cacheTimeSec = null)
    {
        $this->preRender();

        $this->assign($data);
        $rendered = $this->view->render($template, $this->vars);

        // if cache
        $this->tryAddToCache($rendered, $cacheTimeSec);

        $this->response->html->emit($rendered);
    }

    /**
     * Try to add to cache
     * @param string $rendered Html content
     * @param int|null $cacheTimeSec Cache time, 0 or null or int
     */
    private function tryAddToCache(string $rendered, int|null $cacheTimeSec = null)
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
    
}
