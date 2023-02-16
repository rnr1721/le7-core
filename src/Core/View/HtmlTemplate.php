<?php

declare(strict_types=1);

namespace le7\Core\View;

class HtmlTemplate extends HtmlTemplateAbstract
{

    /**
     * @param string $template
     * @return $this
     */
    public function setTemplate(string $template) : self {
        $templatePaths = array(
            $this->topology->getHtmlThemeAppPath().'/'.$template.$this->templateExtension,
            $this->topology->getTemplatesSystemHtmlPath().'/'.$template.$this->templateExtension
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
