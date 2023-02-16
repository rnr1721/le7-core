<?php

declare(strict_types=1);

namespace le7\Core\View\Php;

use le7\Core\Config\TopologyPublicInterface;

class PhpView {

    use PageTrait;

    private TopologyPublicInterface $topologyWeb;
    private Template $template;
    public array $vars = array();

    public function __construct(Template $template, TopologyPublicInterface $topologyWeb) {
        $this->template = $template;
        $this->topologyWeb = $topologyWeb;
    }

    public function include(string $templateFile): string {
        extract($this->vars, EXTR_REFS);
        $template = $this->template->exists($templateFile);
        ob_start();
        include $template;
        return ob_get_clean();
    }

    /**
     * Escape string
     * @param string $varName
     * @param mixed $default
     * @return void
     */
    public function e(string $var): string {
        return htmlspecialchars($var);
    }

    public function ifnot(string $value, int|string|array|bool|null $default): mixed {
        if ($value) {
            return $value;
        }
        return $default;
    }

}
