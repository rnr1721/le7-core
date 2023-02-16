<?php

declare(strict_types=1);

namespace le7\Core\View\Php;

class PhpEngine {
            
    private array $vars = array();
    
    private PhpView $view;
    
    public function __construct(PhpView $view) {
        $this->view = $view;
    }

    public function assign(string $key, mixed $value) {
        $this->vars[$key] = $value;
    }

    public function assignArray($array) {
        foreach ($array as $key=>$value) {
            $this->vars[$key] = $value;
        }
    }

    public function render($template, array $vars = array()) {
        $this->assignArray($vars);
        $this->view->vars = $vars;
        return $this->view->include($template);
    }
    
}
