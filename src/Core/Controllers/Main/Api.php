<?php

declare(strict_types=1);

namespace le7\Core\Controllers\Main;

use le7\Core\GlobalEnvironment;
use le7\Core\Request\Request;
use le7\Core\Response\ResponseApi;
use le7\Core\Instances\RouteHttpInterface;
use le7\Core\Config\TopologyPublicInterface;

class Api extends Main {
    
    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @var ResponseApi
     */
    protected ResponseApi $response;
    /**
     * @var RouteFinalHttpInterface
     */
    public RouteHttpInterface $route;

    protected TopologyPublicInterface $topologyPublic;

    public function __construct(
            GlobalEnvironment $env,
            Request $request,
            ResponseApi $response,
            TopologyPublicInterface $topologyPublic
            ) {
        parent::__construct($env);
        $this->request = $request;
        $this->response = $response;
        $this->topologyPublic = $topologyPublic;
    }
    
    public function indexGetAction() {
        return $this->response->json->emitError(404);
    }
    
    public function indexPostAction() {
        return $this->response->json->emitError(404);
    }
    
    public function indexPutAction() {
        return $this->response->json->emitError(404);
    }
    
    public function indexDeleteAction() {
        return $this->response->json->emitError(404);
    }
    
    public function indexPatchAction() {
        return $this->response->json->emitError(404);
    }
    
}
