<?php

declare(strict_types=1);

namespace Core\DebugPanel\Collectors;

use DebugBar\DataCollector\Renderable;
use DebugBar\DataCollector\DataCollector;

class LocalesCollector extends DataCollector implements Renderable
{

    public function getName()
    {
        return 'locales';
    }

    public function collect()
    {
        return array(
            'locale' => setlocale(LC_ALL, 0)
        );
    }

    public function getWidgets()
    {
        return array(
            "locales" => array(
                "icon" => "",
                "tooltip" => "Current locale",
                "map" => "locales.locale",
                "default" => ""
            ),
        );
    }
}
