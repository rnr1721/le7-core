<?php

return [
    "scriptVersion" => 1,
    // Name of project
    "projectName" => "My Sample Project",
    // Public subdirectory
    "publicSubdir" => "/",
    // Default controller
    "defaultController" => "index",
    //Default action
    "defaultAction" => "index",
    // Controller for 404 page
    "notfoundController" => "notfound",
    // Namespace to find notfound Web controller. May be empty
    "notfoundWebNamespace" => 'App\Controller\Web',
    // Namespace to find notfound API controller. May be empty
    "notfoundApiNamespace" => 'App\Controller\Api\v1',
    // Command namespace (for CLI controllers)
    "commandNamespace" => "App\Controller\Cli",
    // Choose timezone
    "timezone" => "Europe/Kiev",
    // Folder with HTML theme
    "theme" => "main",
    // Error Reporting
    "errorReporting" => true,
    // Extensions dir for current template engine
    "viewExtensions" => "{base}{ds}App{ds}ViewExtensions"
];
