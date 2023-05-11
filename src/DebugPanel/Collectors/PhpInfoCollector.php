<?php

declare(strict_types=1);

namespace Core\DebugPanel\Collectors;

use DebugBar\DataCollector\PhpInfoCollector as PhpInfoOrigCollector;

class PhpInfoCollector extends PhpInfoOrigCollector
{

    public function getWidgets()
    {
        return array(
            "php_version" => array(
                "icon" => "",
                "tooltip" => "Version",
                "map" => "php.version",
                "default" => ""
            ),
        );
    }
}
