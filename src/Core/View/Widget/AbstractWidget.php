<?php

namespace App\Core\View\Widget;

use App\Core\View\HtmlTemplateFactory;
use App\Core\View\HtmlTemplateWidget;
use App\Core\Entity\Group\EntityGroupInterface;

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
