<?php

namespace App\Core\ErrorHandling;

use App\Core\Helpers\ConsoleHelper;
use App\Core\Messages\MessageCollectionInterface;
use App\Core\Config\ConfigInterface;
use App\Core\Config\TopologyFsInterface;
use Psr\Log\LoggerInterface;

class ErrorHandlerCliFactory {

    private ConfigInterface $config;
    private TopologyFsInterface $topology;
    private ErrorCodes $errorCodes;
    private MessageCollectionInterface $messageCollection;
    private LoggerInterface $logger;
    private ConsoleHelper $consoleHelper;

    public function __construct(
            ConfigInterface $config,
            TopologyFsInterface $topology,
            ErrorCodes $errorCodes,
            MessageCollectionInterface $messageCollection,
            LoggerInterface $logger,
            ConsoleHelper $consoleHelper
            ) {
        $this->config = $config;
        $this->topology = $topology;
        $this->errorCodes = $errorCodes;
        $this->messageCollection = $messageCollection;
        $this->logger = $logger;
        $this->consoleHelper = $consoleHelper;
    }

    public function getErrorHandler() {
        $errorHtml = new ErrorToCli($this->config, $this->topology, $this->consoleHelper);
        $errorCollector = new ErrorCollector($errorHtml, $this->errorCodes, $this->messageCollection);
        return new ErrorHandler($this->config, $errorCollector, $this->logger);
    }
    
}
