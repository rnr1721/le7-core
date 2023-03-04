<?php

declare(strict_types=1);

namespace App\Core\ErrorHandling;

use App\Core\Response\Response;
use App\Core\Config\TopologyPublicInterface;
use App\Core\Config\TopologyFsInterface;
use App\Core\Config\ConfigInterface;
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
