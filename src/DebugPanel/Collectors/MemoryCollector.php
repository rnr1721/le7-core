<?php

declare(strict_types=1);

namespace Core\DebugPanel\Collectors;

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
