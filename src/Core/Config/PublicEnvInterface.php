<?php

namespace App\Core\Config;

interface PublicEnvInterface {

    public function export(array $data = array()): string;
}
