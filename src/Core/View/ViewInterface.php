<?php

namespace App\Core\View;

interface ViewInterface
{

    public function render(string $layout, array $vars = array()): string;
}
