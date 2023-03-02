<?php

namespace le7\Core\View;

interface ViewInterface
{

    public function render(string $layout, array $vars = array()): string;
}
