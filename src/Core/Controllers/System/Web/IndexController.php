<?php

namespace le7\Core\Controllers\System\Web;

use le7\Core\View\Php\PhpViewAdapter;
use le7\Core\Controllers\Main\Web;

class IndexController extends Web
{

    public function __construct(PhpViewAdapter $phpView)
    {
        parent::__construct();
        $this->view = $phpView->getView();
    }

    public function indexAction()
    {
        $this->render('index_placeholder.phtml');
    }

    public function indexGetAjax()
    {
        $this->response->json->emitSuccess();
    }

    public function indexPostAjax()
    {
        $this->response->json->emitSuccess();
    }

    public function indexPutAjax()
    {
        $this->response->json->emitSuccess();
    }

    public function indexDeleteAjax()
    {
        $this->response->json->emitSuccess();
    }

}
