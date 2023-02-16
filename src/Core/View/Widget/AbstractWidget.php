<?php

namespace le7\Core\View\Widget;

use le7\Core\View\HtmlTemplateFactory;
use le7\Core\View\HtmlTemplateWidget;
use le7\Core\Entity\Group\EntityGroupInterface;

abstract class AbstractWidget {
    
    protected array $options;
    protected array $data;
    protected HtmlTemplateFactory $htmlTemplateFactory;
    protected HtmlTemplateWidget $htmlTemplate;
    protected EntityGroupInterface $entity;

    public function __construct(HtmlTemplateFactory $htmlTemplateFactory, EntityGroupInterface $entity, array $data = [], array $options = []) {
        $this->entity = $entity;
        $this->data = $data;
        $this->options = $options;
        $this->htmlTemplateFactory = $htmlTemplateFactory;
        $this->htmlTemplate = $htmlTemplateFactory->getHtmlTemplateWidget();
    }
    
    abstract public function render() : string;
    
}
