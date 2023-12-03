<?php

namespace Core\Install;

class PostUpdate
{

    public static function updatePlugins()
    {
        $composerInfo = json_decode(`composer show --all --format=json`, true);

        if ($composerInfo === null && json_last_error() !== JSON_ERROR_NONE) {
            echo self::colorize("Error decoding JSON: " . json_last_error_msg() . "\n", '0;31');
            exit;
        }

        $ds = DIRECTORY_SEPARATOR;

        foreach ($composerInfo['installed'] as $package) {
            if (isset($package['name'])) {
                $packageName = $package['name'];
                $projectRoot = getcwd();
                $pluginRoot = $projectRoot . $ds . 'vendor' . $ds . $packageName;

                $pluginJsonFile = $pluginRoot . $ds . 'le7plugin.json';

                if (file_exists($pluginJsonFile)) {

                    $pluginJsonString = file_get_contents($pluginJsonFile);
                    if (self::isJson($pluginJsonString)) {

                        echo self::colorize('Update configuration of ' . $packageName, 36) . "\r\n";
                        $pluginJsonData = json_decode($pluginJsonString, true);

                        if (isset($pluginJsonData['update_description'])) {
                            echo $pluginJsonData['update_description'] . "\r\n";
                        }

                        self::createDirsIfNotExists($packageName, $pluginJsonData, $projectRoot, $pluginRoot);
                        self::moveFilesIfNotExists($packageName, $pluginJsonData, $projectRoot, $pluginRoot);
                        self::copyFilesIfNotExists($packageName, $pluginJsonData, $projectRoot, $pluginRoot);
                        self::removeFilesIfExists($packageName, $pluginJsonData, $projectRoot, $pluginRoot);
                    } else {
                        echo 'broken configuration of ' . $packageName . " - cannot update configuration\r\n";
                    }
                }
            }
        }
    }

    private static function isJson($string): bool
    {
        json_decode($string);
        return (json_last_error() === JSON_ERROR_NONE);
    }

    private static function colorize($text, $colorCode): string
    {
        return "\033[{$colorCode}m{$text}\033[0m";
    }

    private static function createDirsIfNotExists(string $packageName, array|null $pluginJsonData, $projectRoot, $pluginRoot): void
    {
        if (isset($pluginJsonData['dir'])) {
            if (is_array($pluginJsonData['dir'])) {
                foreach ($pluginJsonData['dir'] as $dirItem => $dirValue) {
                    $dirPath = self::getPath($dirValue, $projectRoot, $pluginRoot);
                    if (!file_exists($dirPath)) {
                        echo "create dir $dirItem - $dirPath\r\n";
                        mkdir($dirPath, 0775, true);
                    }
                }
            } else {
                echo 'wrong plugin configuration ' . $packageName . " - invalid format of dir key\r\n";
            }
        }
    }

    private static function copyFilesIfNotExists(string $packageName, array|null $pluginJsonData, $projectRoot, $pluginRoot): void
    {
        if (isset($pluginJsonData['copy'])) {
            if (is_array($pluginJsonData['copy'])) {
                foreach ($pluginJsonData['copy'] as $copyItem => $copyValue) {
                    $currentSource = self::getPath($copyItem, $projectRoot, $pluginRoot);
                    $currentDestination = self::getPath($copyValue, $projectRoot, $pluginRoot);
                    if (!file_exists($currentDestination)) {
                        if (file_exists($currentSource)) {
                            echo "copy $currentSource to $currentDestination\r\n";
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
    }

    private static function moveFilesIfNotExists(string $packageName, array|null $pluginJsonData, $projectRoot, $pluginRoot): void
    {
        if (isset($pluginJsonData['move'])) {
            if (is_array($pluginJsonData['move'])) {
                foreach ($pluginJsonData['move'] as $renameItem => $renameValue) {
                    $fileToRename = self::getPath($renameItem, $projectRoot, $pluginRoot);
                    $renameTo = self::getPath($renameValue, $projectRoot, $pluginRoot);
                    if (file_exists($renameTo)) {
                        echo "Can not move $fileToRename to $renameTo: file exists\r\n";
                    } else {
                        if (rename($fileToRename, $renameTo)) {
                            echo "Move $fileToRename to $renameTo\r\n";
                        } else {
                            echo "File $fileToRename not exists" . "\r\n";
                        }
                    }
                }
            } else {
                echo 'wrong plugin configuration ' . $packageName . " - invalid format of copy key\r\n";
            }
        }
    }

    private static function removeFilesIfExists(string $packageName, array|null $pluginJsonData, $projectRoot, $pluginRoot): void
    {
        if (isset($pluginJsonData['remove'])) {
            if (is_array($pluginJsonData['remove'])) {
                foreach ($pluginJsonData['remove'] as $fileItem => $fileValue) {
                    $fileToDelete = self::getPath($fileValue, $projectRoot, $pluginRoot);
                    if (file_exists($fileToDelete)) {
                        echo "Remove file $fileItem - $fileToDelete\r\n";
                        unlink($fileToDelete);
                    }
                }
            } else {
                echo 'wrong plugin configuration ' . $packageName . " - invalid format of dir key\r\n";
            }
        }
    }

    public static function getPath($path, $projectRoot, $pluginRoot): string
    {
        $search = [
            '{project_root}',
            '{plugin_root}'
        ];
        $replace = [
            $projectRoot,
            $pluginRoot
        ];
        return str_replace($search, $replace, $path);
    }
}
