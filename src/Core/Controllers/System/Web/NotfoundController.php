<?php

namespace App\Core\Controllers\System\Web;

use Psr\Log\LoggerInterface;
use App\Core\View\Php\PhpViewAdapter;
use App\Core\Controllers\Main\Web;

class NotfoundController extends Web
{

    private LoggerInterface $ulog;

    public function __construct(PhpViewAdapter $phpView, LoggerInterface $ulog)
    {
        parent::__construct();
        $this->view = $phpView->getView();
        $this->ulog = $ulog;
    }

    public function indexAction()
    {
        $this->ulog->alert('WEB 404 not found ' . $this->request->getUri()->getQuery());
        $this->render('error404.phtml');
    }

    public function indexGetAjax()
    {
        $this->response->json->emitError(404);
    }

    public function indexPostAjax()
    {
        $this->response->json->emitError(404);
    }

    public function indexPutAjax()
    {
        $this->response->json->emitError(404);
    }

    public function indexDeleteAjax()
    {
        $this->response->json->emitError(404);
    }

}
