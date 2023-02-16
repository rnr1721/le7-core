<?php

namespace le7\Core\ErrorHandling;

use le7\Core\Config\TopologyPublicInterface;
use le7\Core\Response\Response;
use le7\Core\Messages\MessageCollectionInterface;
use le7\Core\Config\ConfigInterface;
use le7\Core\Config\TopologyFsInterface;
use Psr\Log\LoggerInterface;

class ErrorHandlerHttpFactory {

    private ConfigInterface $config;
    private TopologyFsInterface $topology;
    private TopologyPublicInterface $topologyWeb;
    private ErrorCodes $errorCodes;
    private MessageCollectionInterface $messageCollection;
    private Response $response;
    private LoggerInterface $logger;

    public function __construct(
            ConfigInterface $config,
            TopologyFsInterface $topology,
            TopologyPublicInterface $topologyWeb,
            ErrorCodes $errorCodes,
            MessageCollectionInterface $messageCollection,
            Response $response,
            LoggerInterface $logger
            ) {
        $this->config = $config;
        $this->topology = $topology;
        $this->topologyWeb = $topologyWeb;
        $this->errorCodes = $errorCodes;
        $this->messageCollection = $messageCollection;
        $this->response = $response;
        $this->logger = $logger;
    }

    public function getErrorHandlerHtml() {
        $errorHtml = new ErrorToHtml($this->config, $this->topology, $this->topologyWeb,$this->response);
        return $this->getErrorHandler($errorHtml);
    }

    public function getErrorHandlerJson() {
        $errorJson = new ErrorToJson($this->config, $this->topology, $this->topologyWeb, $this->response);
        return $this->getErrorHandler($errorJson);
    }

    private function getErrorHandler(ErrorInterface $errorOutput) {
        $errorCollector = new ErrorCollector($errorOutput, $this->errorCodes, $this->messageCollection);
        return new ErrorHandler($this->config, $errorCollector, $this->logger);
    }
    
}
