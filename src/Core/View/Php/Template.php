<?php

declare(strict_types=1);

namespace App\Core\View\Php;

use \Exception;

class Template {

    private array $path;

    public function exists(string $template): string {
        foreach ($this->path as $dir) {
            $current = $dir . DIRECTORY_SEPARATOR . $template;
            if (file_exists($current)) {
                return $current;
            }
        }

        throw new Exception("Template not found:" . $template);
    }
    
    public function setPath(string $path) {
        $this->path[] = $path;
    }
    
}
