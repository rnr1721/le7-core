<?php

namespace le7\Core\View;

use le7\Core\Config\TopologyFsInterface;

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
