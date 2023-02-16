<?php

namespace le7\Core\DebugPanel;

use DebugBar\DataCollector\AssetProvider;
use DebugBar\DataCollector\Renderable;
use DebugBar\DataCollector\DataCollector;
use \RedBeanPHP\Logger;

class RedbeanPhpCollector extends DataCollector implements Renderable, AssetProvider {

    /**
     * Whether to show or not '--keep-cache' in your queries.
     * @var boolean
     */
    public static $showKeepCache = false;

    /**
     * Logger must implement RedBean's Logger interface.
     * @var \RedBeanPHP\Logger
     */
    protected Logger $logger;

    /**
     * Set RedBean's logger
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Collect all the executed queries by now.
     */
    public function collect()
    {


        return array(
            'nb_statements' => 0,
            'statements' => array()
        );
    }

    public function getName()
    {
        return 'database';
    }

    /**
     * @return array
     */
    public function getWidgets()
    {
        return array(
            "database" => array(
                "icon" => "database",
                "widget" => "PhpDebugBar.Widgets.SQLQueriesWidget",
                "map" => "database",
                "default" => "[]"
            ),
            "database:badge" => array(
                "map" => "database.nb_statements",
                "default" => 0
            )
        );
    }

    /**
     * @return array
     */
    public function getAssets()
    {
        return array(
            'css' => 'widgets/sqlqueries/widget.css',
            'js' => 'widgets/sqlqueries/widget.js'
        );
    }
    
}
