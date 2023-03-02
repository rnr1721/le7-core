<?php

declare(strict_types=1);

namespace le7\Core\View\Php;

use le7\Core\View\ViewInterface;

class PhpView implements ViewInterface
{

    private array $vars = array();
    private PhpRenderEngine $view;

    public function __construct(PhpRenderEngine $view)
    {
        $this->view = $view;
    }

    public function render(string $template, array $vars = []): string
    {
        $this->view->vars = $vars;
        return $this->view->include($template);
    }

}
