<?php

namespace le7\Core\View\Php;

use le7\Core\View\ViewInterface;
use le7\Core\View\ViewAdapterInterface;
use le7\Core\Config\TopologyPublicInterface;
use le7\Core\Config\TopologyFsInterface;

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
