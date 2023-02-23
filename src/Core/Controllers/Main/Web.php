<?php

declare(strict_types=1);

namespace le7\Core\Controllers\Main;

use le7\Core\User\UserIdentityFactory;
use le7\Core\View\Widget\WidgetFactory;
use le7\Core\Config\CodePartsFactory;
use le7\Core\Config\PublicEnvFactory;
use le7\Core\Config\PublicEnvironment;
use le7\Core\Config\TopologyPublicInterface;
use le7\Core\DebugPanel\DebugPanel;
use le7\Core\GlobalEnvironment;
use le7\Core\Helpers\UrlHelper;
use le7\Core\Instances\RouteHttpInterface;
use le7\Core\Messages\MessageCollectionInterface;
use le7\Core\Messages\MessageFactory;
use le7\Core\Messages\MessageGetInterface;
use le7\Core\Messages\MessagePutInterface;
use le7\Core\Request\Request;
use le7\Core\Response\ResponseWeb;
use le7\Core\View\Php\PageTrait;

class Web extends Main {

    use PageTrait;

    protected UserIdentityFactory $userIdentityFactory;
    protected WidgetFactory $widgets;
    private MessageGetInterface $messageGet;
    private MessagePutInterface $messagePut;
    protected MessageCollectionInterface $messagesFlash;
    protected int|null $cacheLifetime = null;
    private string|null $cacheIndex = null;
    private DebugPanel $debugbar;
    protected array $vars = array();
    protected Request $request;
    protected ResponseWeb $response;
    public RouteHttpInterface $route;
    protected TopologyPublicInterface $topologyWeb;
    private CodePartsFactory $codePartsFactory;
    private UrlHelper $urlHelper;
    private PublicEnvFactory $publicEnvFactory;
    protected PublicEnvironment $publicEnvironment;

    /**
     * ControllerWeb constructor.
     */
    public function __construct(
            GlobalEnvironment $env,
            Request $request,
            ResponseWeb $response,
            TopologyPublicInterface $topologyPublic,
            UrlHelper $urlHelper,
            PublicEnvFactory $publicEnvFactory,
            CodePartsFactory $codePartsFactory,
            DebugPanel $debugbar,
            MessageFactory $messageFactory,
            WidgetFactory $widgetFactory,
            UserIdentityFactory $userIdentityFactory
    ) {

        parent::__construct($env);

        $this->request = $request;
        $this->response = $response;
        $this->topologyWeb = $topologyPublic;
        $this->debugbar = $debugbar;
        $this->urlHelper = $urlHelper;
        $this->codePartsFactory = $codePartsFactory;
        $this->publicEnvFactory = $publicEnvFactory;
        $this->publicEnvironment = $publicEnvFactory->getEnvHtml();
        $this->cacheLifetime = $this->config->getCacheLifetime();
        $this->messageGet = $messageFactory->getGetStorage();
        $this->messagePut = $messageFactory->getPutStorage();
        $this->messagesFlash = $messageFactory->newInstance();
        $this->widgets = $widgetFactory;
        $this->userIdentityFactory = $userIdentityFactory;

        if ($this->config->getUserManagementOn()) {
            $userIdentity = $this->userIdentityFactory->getUserWeb();
            $this->user = $userIdentity->getUser($this->dbConnection);
        }
    }

    public function preRender() {

        $otherLanguages = $this->urlHelper->getLanguageUrlVariants($this->route);

        $this->handleFlashMessages();

        $vars = array(
            'url' => $this->urlHelper,
            'lang' => $this->locales->getCurrentLocaleShortname(),
            'urlLibs' => $this->topologyWeb->getLibsUrl(),
            'urlThemes' => $this->topologyWeb->getThemesUrl(),
            'urlCss' => $this->topologyWeb->getCssUrl(),
            'urlJs' => $this->topologyWeb->getJsUrl(),
            'urlTheme' => $this->topologyWeb->getThemeUrl(),
            'urlImages' => $this->topologyWeb->getImagesUrl(),
            'urlFonts' => $this->topologyWeb->getFontsUrl(),
            'translate' => $this->translate,
            'languages' => $this->locales->getLocalesByShortname(),
            'locales' => $this->locales,
            'route' => $this->route->exportArray(),
            'otherLanguages' => $otherLanguages,
            'env' => $this->publicEnvironment->export(),
            'styles' => '',
            'title' => '',
            'header' => '',
            'importmap' => '',
            'scripts_header' => '',
            'scripts_footer' => '',
            //'canonical' => $this->route->getCanonicalUri(),
            'microformat' => '',
            'keywords' => '',
            'description' => '',
            'messages' => $this->messages->getAll(),
            'projectName' => $this->config->getProjectName(),
            'content' => '',
            'config' => $this->config,
            'uconfig' => $this->uconfig,
            'snippets_top' => $this->codePartsFactory->getStatTop() ?? '',
            'snippets_middle' => $this->codePartsFactory->getStatMiddle() ?? '',
            'snippets_bottom' => $this->codePartsFactory->getStatBottom() ?? '',
            'user' => $this->user
        );

        foreach ($vars as $cKey => $cValue) {
            if (!isset($this->vars[$cKey])) {
                $this->vars[$cKey] = $cValue;
            }
        }

        if ($this->debugbar->canStart()) {
            $this->setScriptLib('debugbar/assets.js');
            $this->setStyleLib('debugbar/assets.css');

            if ($this->dbConnection->isConnected()) {
                $this->debugbar->registerDatabase($this->db->getLogger()->getLogs());
            }

            $this->debugbar->registerResponse($this->response->getResponseCode());

            $this->debugbar->registerArray($this->config->exportConfig(), "Config");

            // Put messages in debugger
            foreach ($this->messages->getAlerts(true) as $alert) {
                $this->debugbar->setMessage('Alert:' . $alert, "alert");
            }
            foreach ($this->messages->getErrors(true) as $error) {
                $this->debugbar->setMessage('Error:' . $error, "error");
            }
            foreach ($this->messages->getInfos(true) as $info) {
                $this->debugbar->setMessage('info:' . $info, "info");
            }
            foreach ($this->messages->getQuestions(true) as $question) {
                $this->debugbar->setMessage('question:' . $question, "info");
            }
            foreach ($this->messages->getWarnings(true) as $warning) {
                $this->debugbar->setMessage('Warnings:' . $warning, "warning");
            }

            // Route
            $route = array(
                'Method' => $this->route->getMethod(),
                'Uri' => $this->route->getUri(),
                'URL params' => $this->route->getParams(),
                'Language' => $this->route->getLanguage(),
                'Controller' => $this->route->getController(),
                'Action' => $this->route->getAction(),
                'Class' => $this->route->getControllerClass(),
                'Action method' => $this->route->getActionMethod(),
                'Proposed response' => $this->route->getResponse(),
                'Base uri' => $this->route->getBase(),
                'Type' => $this->route->getType(),
                'Case' => $this->route->getCase()
            );
            //$this->debugbar->registerRoute($route);

            $this->debugbar->registerArray($route, "Route");

            //$this->debugbar->setMessage(strval($this->response->getResponseCode()));
            $this->vars['scripts_footer'] .= $this->debugbar->renderBody();
        }
    }

    public function indexGetAjax() {
        return $this->response->json->emitError(404);
    }

    public function indexPostAjax() {
        return $this->response->json->emitError(404);
    }

    public function indexPutAjax() {
        return $this->response->json->emitError(404);
    }

    public function indexDeleteAjax() {
        return $this->response->json->emitError(404);
    }

    public function indexPatchAjax() {
        return $this->response->json->emitError(404);
    }

    public function setCacheIndex(string $cacheIndex): self {
        $name = str_replace(' ', '_', $cacheIndex);
        $this->cacheIndex = 'pg_' . $name . '_' . $this->locales->getCurrentLocaleShortname();
        return $this;
    }

    public function tryEmitFromCache() {
        if ($this->cacheIndex !== null) {
            $rendered = $this->cache->get($this->cacheIndex);
            if ($rendered) {
                $this->response->html->emit($rendered);
            }
        }
    }

    public function tryAddToCache(string $rendered, int|null $cacheTimeSec = null) {
        if ($this->cacheIndex !== null && $cacheTimeSec !== null) {
            if ($cacheTimeSec === 0) {
                $this->cache->set($this->cacheIndex, $rendered);
            } else {
                $this->cache->set($this->cacheIndex, $rendered, $cacheTimeSec);
            }
        }
    }

    public function setContent($contentTemplate) {
        $this->vars['content'] = $contentTemplate;
    }

    protected function handleFlashMessages() {
        // Flash messages in session or cookies (depend config params)
        $this->messages->loadMessages($this->messageGet);
        $this->messagesFlash->putMessages($this->messagePut);
    }

}
