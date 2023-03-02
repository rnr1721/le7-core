<?php

declare(strict_types=1);

namespace le7\Core\Controllers\Main;

use le7\Core\User\UserManager;
use le7\Core\Request\Request;
use le7\Core\Response\ResponseApi;
use le7\Core\Instances\RouteHttpInterface;
use le7\Core\Config\TopologyPublicInterface;

class Api extends Main {

    public UserManager $userIdentityFactory;

    /**
     * @var Request
     */
    public Request $request;

    /**
     * @var ResponseApi
     */
    public ResponseApi $response;

    /**
     * @var RouteFinalHttpInterface
     */
    public RouteHttpInterface $route;
    public TopologyPublicInterface $topologyPublic;

    public function __construct() {

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

    public function indexOptionsAction() {
        return $this->response->json->emitError(200);
    }
    
}
