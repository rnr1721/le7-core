<?php

declare(strict_types=1);

namespace Core\EventListeners;

use Core\Interfaces\WebPageInterface;
use Core\Interfaces\MessageCollectionInterface;
use Core\DebugPanel\Collectors\ResponseCollector;
use Core\DebugPanel\DebugPanel;
use Core\EventDispatcher\Listener;

class DebugBarListener extends Listener
{

    private DebugPanel $debugPanel;
    private MessageCollectionInterface $messages;
    private WebPageInterface $webPage;

    public function __construct(
            DebugPanel $debugPanel,
            MessageCollectionInterface $messages,
            WebPageInterface $webPage
    )
    {
        $this->debugPanel = $debugPanel;
        $this->messages = $messages;
        $this->webPage = $webPage;
    }

    public function trigger(): void
    {

        if ($this->debugPanel->canStart()) {

            $responseCode = $this->event->getResponseCode();

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

            $responseCollector = new ResponseCollector($responseCode);
            $this->debugPanel->addCollector($responseCollector);

            $this->webPage->setScriptFromGlobal('debugbar/assets.js');
            $this->webPage->setStyleFromGlobal('debugbar/assets.css');
            $this->webPage->appendScripts($this->debugPanel->render(), false);
        }
    }

}
