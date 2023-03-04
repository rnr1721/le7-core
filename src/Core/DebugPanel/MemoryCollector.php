<?php

namespace App\Core\DebugPanel;

use DebugBar\DataCollector\MemoryCollector as MemoryOrigCollector;

class MemoryCollector extends MemoryOrigCollector
{
    public function getWidgets()
    {
        return array(
            "memory" => array(
                "icon" => "",
                "tooltip" => "Memory Usage",
                "map" => "memory.peak_usage_str",
                "default" => "'0B'"
            )
        );
    }
}
