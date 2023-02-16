<?php

namespace le7\Core\Config;

interface PublicEnvInterface {

    public function export(array $data = array()): string;
}
