<?php

namespace App\Core\View\Php;

use App\Core\View\ViewInterface;
use App\Core\View\ViewAdapterInterface;
use App\Core\Config\TopologyPublicInterface;
use App\Core\Config\TopologyFsInterface;

class PhpViewAdapter implements ViewAdapterInterface {

    private TopologyPublicInterface $topologyWeb;
    private TopologyFsInterface $topologyFs;

    public function __construct(TopologyFsInterface $topologyFs, TopologyPublicInterface $topologyWeb) {
        $this->topologyFs = $topologyFs;
        $this->topologyWeb = $topologyWeb;
    }

    public function getView() : ViewInterface {
        $template = new Template();
        $template->setPath($this->topologyFs->getHtmlThemeAppPath());
        $template->setPath($this->topologyFs->getTemplatesSystemHtmlPath());
        $phpView = new PhpRenderEngine($template, $this->topologyWeb);
        return new PhpView($phpView);
    }

}
