<?php

namespace le7\Core\Controllers\System\Cli;

use le7\Core\Controllers\Main\Cli;

class CacheController extends Cli {

    public function indexAction() {
        $this->stdout('usage: cache:delete' . "\r\n");
    }

    public function deleteAction() {
        $this->clearFolder($this->topologyFs->getTempDir());
        $this->clearFolder($this->topologyFs->getObjectCachePath());
        $this->clearFolder($this->topologyFs->getSmartyCachePath());
        $this->clearFolder($this->topologyFs->getRoutesCachePath());
        $this->clearFolder($this->topologyFs->getConfigDiContainers());
        $this->stdout("Cache clean");
    }

    private function clearFolder(string $folder) {
        $files = glob($folder . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        $this->stdout($folder . ' ' . _("cleared") . "\r\n");
    }

}
