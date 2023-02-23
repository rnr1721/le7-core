<?php

declare(strict_types=1);

namespace le7\Core\Controllers\Main;

use le7\Core\User\UserIdentityFactory;
use le7\Core\GlobalEnvironment;
use le7\Core\Request\Request;
use le7\Core\Response\ResponseApi;
use le7\Core\Instances\RouteHttpInterface;
use le7\Core\Config\TopologyPublicInterface;

class Api extends Main {
    
    protected UserIdentityFactory $userIdentityFactory;

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
            TopologyPublicInterface $topologyPublic,
            UserIdentityFactory $userIdentityFactory
            ) {
        parent::__construct($env);
        $this->request = $request;
        $this->response = $response;
        $this->topologyPublic = $topologyPublic;
        $this->userIdentityFactory = $userIdentityFactory;
        
        if ($this->config->getUserManagementOn()) {
            $userIdentity = $this->userIdentityFactory->getUserApi();
            $this->user = $userIdentity->getUser($this->dbConnection);
        }
        
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
