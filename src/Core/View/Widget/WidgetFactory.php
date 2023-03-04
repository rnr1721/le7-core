<?php

namespace App\Core\View\Widget;

use App\Core\Entity\Group\EntityGroupInterface;
use App\Core\View\HtmlTemplateFactory;

class WidgetFactory {
    
    private HtmlTemplateFactory $htmlTemplateFactory;

    public function __construct(HtmlTemplateFactory $htmlTemplateFactory) {
        $this->htmlTemplateFactory = $htmlTemplateFactory;
    }
    
    public function getGridViewBootstrap(EntityGroupInterface $entity, array $data, array $options) : GridViewBootstrapWidget {
        return new GridViewBootstrapWidget($this->htmlTemplateFactory, $entity, $data, $options);
    }
    
}
