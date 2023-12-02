<?php

echo colorize("Start of update le7 PHP MVC framework plugins" . "\r\n", "1;34");

$composerInfo = json_decode(`composer show --all --format=json`, true);

if ($composerInfo === null && json_last_error() !== JSON_ERROR_NONE) {
    echo colorize("Error decoding JSON: " . json_last_error_msg() . "\n", '0;31');
    exit;
}

$ds = DIRECTORY_SEPARATOR;

foreach ($composerInfo['installed'] as $package) {
    if (isset($package['name'])) {
        $packageName = $package['name'];
        $packagePath = $projectRoot = realpath(__DIR__ . '/../../../../') . $ds . $packageName;

        $pluginJsonFile = $packagePath . $ds . 'le7plugin.json';

        if (file_exists($pluginJsonFile)) {
            $pluginJsonString = file_get_contents($pluginJsonFile);
            if (isJson($pluginJsonString)) {
                echo 'update configuration of ' . $packageName . "\r\n";
                $pluginJsonData = json_decode($pluginJsonString, true);
                if (isset($pluginJsonData['run']) && is_string($pluginJsonData['run'])) {
                    $pluginUpdateFile = $packagePath . $ds . $pluginJsonData['run'];
                    if (file_exists($pluginUpdateFile)) {
                        if (isset($pluginJsonData['update_description'])) {
                            echo 'What happens: ' . $pluginJsonData['update_description'] . "\r\n";
                        }
                        include($pluginUpdateFile);
                    } else {
                        echo 'no plugin update file ' . $pluginUpdateFile . ' in package ' . $packageName . "\r\n";
                    }
                }
                if (isset($pluginJsonData['dir'])) {
                    if (is_array($pluginJsonData['dir'])) {
                        foreach ($pluginJsonData['dir'] as $dirItem => $dirValue) {
                            echo "create dir $dirItem - $dirValue\r\n";
                            $newDir = $dirValue;
                            if (!file_exists($dirValue)) {
                                mkdir($dirValue, 0775, true);
                            }
                        }
                    } else {
                        echo 'wrong plugin configuration ' . $packageName . " - invalid format of dir key\r\n";
                    }
                }
                if (isset($pluginJsonData['copy'])) {
                    if (is_array($pluginJsonData['copy'])) {
                        foreach ($pluginJsonData['copy'] as $copyItem => $copyValue) {
                            $currentSource = $packagePath . $ds . ltrim($copyItem, $ds);
                            $currentDestination = ltrim($copyValue, $ds);
                            if (!file_exists($currentDestination)) {
                                if (file_exists($currentSource)) {
                                    copy($currentSource, $currentDestination);
                                } else {
                                    echo "File $currentSource not exists" . "\r\n";
                                }
                            }
                        }
                    } else {
                        echo 'wrong plugin configuration ' . $packageName . " - invalid format of copy key\r\n";
                    }
                }
            } else {
                echo 'broken configuration of ' . $packageName . " - cannot update configuration\r\n";
            }
        }
    }
}

echo colorize("\nUpdate of le7 PHP MVC framework plugins finished\n", '1;34');

function isJson($string)
{
    json_decode($string);
    return (json_last_error() === JSON_ERROR_NONE);
}

function colorize($text, $colorCode)
{
    return "\033[{$colorCode}m{$text}\033[0m";
}
