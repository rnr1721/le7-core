<?php

namespace App\Core\View;

class HtmlTemplateWidget extends HtmlTemplateAbstract {
    
    /**
     * @param string $template
     * @return $this
     */
    public function setTemplate(string $template) : self {
        $templatePaths = array(
            $this->topology->getWidgetTemplateDir().'/'.$template.$this->templateExtension,
            $this->topology->getWidgetTemplateSystemDir().'/'.$template.$this->templateExtension
        );
        foreach ($templatePaths as $templatePath) {
            if (file_exists($templatePath)) {
                $this->templatePath = $templatePath;
                return $this;
            }
        }
        trigger_error('Template not found: '.$template,E_USER_ERROR);
    }

}
