<?php

declare(strict_types=1);

namespace App\Core\ErrorHandling;

use App\Core\Response\Response;
use App\Core\Config\TopologyPublicInterface;
use App\Core\Config\TopologyFsInterface;
use App\Core\Config\ConfigInterface;
use Throwable;

class ErrorToJson extends ErrorToMain implements ErrorInterface
{

    protected Response $response;
    protected TopologyPublicInterface $topologyWeb;
    
    public function __construct(ConfigInterface $config, TopologyFsInterface $topology, TopologyPublicInterface $topologyPublic, Response $response) {
        parent::__construct($config, $topology);
        $this->topologyWeb = $topologyPublic;
        $this->response = $response;
    }
    
    public function show(Throwable|null $exception, array $errors): void
    {
        if (ob_get_contents()) {
            ob_clean();
        }
        $result = array(
            'result' => null,
            'success' => false,
            'errors' => array()
        );
        if (!empty($errors)) {
            foreach ($errors as $error) {
                $result['errors'][] = $error['errorType'] . ' > ' . $error['errorString'] . ' > ' . $error['errorFile'] . ' (' . $error['errorLine'] . ')';
            }
        }
        if (!empty($exception)) {
            $result['errors'][] = $exception->getMessage() . ' > ' . $exception->getFile() . ' (' . $exception->getLine() . ')';
            foreach ($exception->getTrace() as $traceItem) {
                $place = '';
                if (!empty($traceItem['class'])) {
                    $place .= ' > ' . $traceItem['class'];
                }
                if (!empty($traceItem['function'])) {
                    $place .= ' > ' . $traceItem['function'];
                }
                $result['errors'][] = $place . ' > ' . $traceItem['file'] . ' > (' . $traceItem['line'] . ')';
            }
        }
        $output = json_encode($result);
        $this->response->setBody($output,true);
        $this->response->setResponseCode(503);
        $this->response->setHeader('Content-Type', 'application/json');
        $this->response->emit();
    }
}
