<?php

namespace le7\Core\View\Php;

use le7\Core\Config\TopologyPublicInterface;
use le7\Core\Config\TopologyFsInterface;

class PhpViewFactory {

    private TopologyPublicInterface $topologyWeb;
    private TopologyFsInterface $topologyFs;

    public function __construct(TopologyFsInterface $topologyFs, TopologyPublicInterface $topologyWeb) {
        $this->topologyFs = $topologyFs;
        $this->topologyWeb = $topologyWeb;
    }

    public function getPhpView() {
        $template = new Template();
        $template->setPath($this->topologyFs->getHtmlThemeAppPath());
        $template->setPath($this->topologyFs->getTemplatesSystemHtmlPath());
        $phpView = new PhpView($template, $this->topologyWeb);
        return new PhpEngine($phpView);
    }

}
