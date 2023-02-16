<?php

declare(strict_types=1);

namespace le7\Core\ErrorHandling;

use le7\Core\Response\Response;
use le7\Core\Config\TopologyPublicInterface;
use le7\Core\Config\TopologyFsInterface;
use le7\Core\Config\ConfigInterface;
use Throwable;

class ErrorToHtml extends ErrorToMain implements ErrorInterface {

    protected TopologyPublicInterface $topologyWeb;
    protected Response $response;

    public function __construct(ConfigInterface $config, TopologyFsInterface $topology, TopologyPublicInterface $topologyPublic, Response $response) {
        parent::__construct($config, $topology);
        $this->topologyWeb = $topologyPublic;
        $this->response = $response;
    }

    /**
     * @param Throwable|null $exception
     * @param array $errors
     */
    public function show(Throwable|null $exception, array $errors): void {
        if (ob_get_contents()) {
            ob_clean();
        }
        ob_start();
        include $this->topology->getErrorTemplateFolder() . '/View.phtml';
        $result = ob_get_clean();
        ob_flush();
        $this->response->setBody($result, true);
        $this->response->setHeader('Content-Type', 'text/html');
        $this->response->setResponseCode(503);
        $this->response->emit();
        exit;
    }

}
