<?php

namespace App\Core\View;

use App\Core\Config\TopologyFsInterface;

class HtmlTemplateFactory {

    private TopologyFsInterface $topologyFs;
    
    public function __construct(TopologyFsInterface $topologyFs) {
        $this->topologyFs = $topologyFs;
    }
    
    public function getHtmlTemplate() : HtmlTemplate {
        return new HtmlTemplate($this->topologyFs);
    }
    
    public function getHtmlTemplateWidget() : HtmlTemplateWidget {
        return new HtmlTemplateWidget($this->topologyFs);
    }
    
}
