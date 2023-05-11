<?php

namespace Core\DebugPanel\Collectors;

use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\Renderable;

class ResponseCollector extends DataCollector implements Renderable
{

    private int $code;

    public function __construct(int $responseCode)
    {
        $this->code = $responseCode;
    }

    public function getName(): string
    {
        return 'response';
    }

    public function collect(): array
    {
        return array(
            'code' => $this->code
        );
    }

    public function getWidgets(): array
    {
        return array(
            "response_code" => array(
                "icon" => "",
                "tooltip" => "Server status",
                "map" => "response.code",
                "default" => ""
            ),
        );
    }

}
