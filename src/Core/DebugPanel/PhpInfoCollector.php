<?php

namespace le7\Core\DebugPanel;

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
