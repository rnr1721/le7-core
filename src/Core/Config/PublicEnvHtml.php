<?php

namespace le7\Core\Config;

class PublicEnvHtml implements PublicEnvInterface {

    public function export(array $data = array()): string {
        $result = "<script>\r\n";
        foreach ($data as $item=>$value) {
            if (is_string($value)) {
                $result .= "    const $item = '$value';\r\n";
            }
            if (is_array($value)) {
                $result .= "    const $item = '". json_encode($value)."';\r\n";
            }
        }
        $result .= "</script>\r\n";
        return $result;
    }

}
