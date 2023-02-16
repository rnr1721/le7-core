<?php

declare(strict_types=1);

namespace le7\Core\View;

use le7\Core\Config\TopologyFsInterface;

abstract class HtmlTemplateAbstract
{

    protected string $templateExtension = '.phtml';

    protected TopologyFsInterface $topology;

    protected string $templatePath = '';
    protected array $templateData = array();

    public function __construct(TopologyFsInterface $topology) {
        $this->topology = $topology;
    }

    /**
     * @param string $templatePath
     * @return bool
     */
    public function setTemplatePath(string $templatePath): bool
    {
        if (!file_exists($templatePath)) {
            trigger_error('Template not found',E_USER_ERROR);
        }
        $this->templatePath = $templatePath;
        return true;
    }

    abstract public function setTemplate(string $template) : self;

    /**
     * @param string $key
     * @param mixed $value
     * @return HtmlTemplate
     */
    public function assign(string $key, mixed $value):self
    {
        $this->templateData[$key] = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function compile(): string
    {
        if (!empty($this->templatePath)) {
            ob_start();
            extract($this->templateData);
            if (!empty($this->templatePath)) {
                include($this->templatePath);
            }
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
        return '';
    }

    public function e(string|null $text): string {
        if (is_null($text)) {
            throw new Exception("HtmlTemplateAbstract::e() requere text but null input");
        }
        return htmlspecialchars($text);
    }
    
}
