<?php

namespace le7\Core\EventDispatcher;

use le7\Core\Config\TopologyFsInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

class EventInvoker {

    protected TopologyFsInterface $topology;
    protected ListenerProviderInterface $listeners;


    public function __construct(TopologyFsInterface $topologyFs,ListenerProviderInterface $listeners) {
        $this->topology = $topologyFs;
        $this->listeners = $listeners;
    }
    public function processEvents() {
        $eventsCfg = $this->topology->getConfigUserPath().DIRECTORY_SEPARATOR.'events.php';
        if (file_exists($eventsCfg)) {
            $events = require $eventsCfg;
            foreach ($events as $key=>$eventValue) {
                $this->listeners->on($eventValue[0],$eventValue[1],$key);
            }
        }
    }
}
