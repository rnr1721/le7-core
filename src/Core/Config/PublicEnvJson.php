<?php

namespace App\Core\Config;

class PublicEnvJson implements PublicEnvInterface {

    public function export(array $data = array()): string {
        return json_encode($data);
    }

}
