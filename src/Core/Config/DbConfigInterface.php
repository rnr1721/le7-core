<?php

namespace le7\Core\Config;

interface DbConfigInterface {
    public function getDbDriver() : string;
    public function getDbUSer() : string;
    public function getDbHost() : string;
    public function getDbPass() : string;
    public function getDbName() : string;
    public function getDbPrfx() : string;
    public function getDbFreeze() : bool;
}
