<?php

declare(strict_types=1);

namespace App\Core\Controllers\Main;

use App\Core\User\UserManager;
use App\Core\Request\Request;
use App\Core\Response\ResponseApi;
use App\Core\Instances\RouteHttpInterface;
use App\Core\Config\TopologyPublicInterface;

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
