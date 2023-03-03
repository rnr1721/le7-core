<?php

namespace le7\Core\DebugPanel;

use le7\Core\Request\Request;
use le7\Core\Config\ConfigInterface;
use le7\Core\Response\Response;
use le7\Core\Database\DatabaseFactory;
use le7\Core\Messages\MessageCollectionInterface;

class DebugPanelRun
{

    private ConfigInterface $config;
    private Request $request;
    private Response $response;
    private DebugPanel $debugPanel;
    private DatabaseFactory $dbFactory;
    private MessageCollectionInterface $messages;

    public function __construct(
            DebugPanel $debugPanel,
            ConfigInterface $config,
            DatabaseFactory $dbFactory,
            MessageCollectionInterface $messages,
            Request $request,
            Response $response)
    {
        $this->config = $config;
        $this->debugPanel = $debugPanel;
        $this->dbFactory = $dbFactory;
        $this->messages = $messages;
        $this->response = $response;
        $this->request = $request;
    }

    public function render(): string
    {
        if ($this->debugPanel->canStart()) {
            if ($this->dbFactory->getDatabaseConnection()->isConnected()) {
                $this->debugPanel->registerDatabase($this->dbFactory->getDatabase()->getLogger()->getLogs());
            }
            $this->debugPanel->registerResponse($this->response->getResponseCode());
            $this->debugPanel->registerArray($this->config->exportConfig(), "Config");
            // Put messages in debugger
            foreach ($this->messages->getAlerts(true) as $alert) {
                $this->debugPanel->setMessage('Alert:' . $alert, "alert");
            }
            foreach ($this->messages->getErrors(true) as $error) {
                $this->debugPanel->setMessage('Error:' . $error, "error");
            }
            foreach ($this->messages->getInfos(true) as $info) {
                $this->debugPanel->setMessage('info:' . $info, "info");
            }
            foreach ($this->messages->getQuestions(true) as $question) {
                $this->debugPanel->setMessage('question:' . $question, "info");
            }
            foreach ($this->messages->getWarnings(true) as $warning) {
                $this->debugPanel->setMessage('Warnings:' . $warning, "warning");
            }
            
            // Route
            $route = $this->request->getAttribute('route');
            $routeArray = array(
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
                'Middleware' => $route->getMiddleware()
            );
            
            $this->debugPanel->registerArray($routeArray, "Route");

            return $this->debugPanel->renderBody();
        }
        return '';
    }

}
