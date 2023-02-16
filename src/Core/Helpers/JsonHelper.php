<?php

namespace le7\Core\Helpers;

class JsonHelper
{

    public function isJson(string $string) : bool {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Convert json to array
     * if input string incorrect, it return empty array
     * @param string $string
     * @return array
     */
    public function fromStringToArray(string $string) : array {
        $res = array();
        if (!$this->isJson($string)) {
            return $res;
        }
        $tmp = json_decode($string);
        foreach ($tmp as $item) {
            $res[$item] = $item;
        }
        return $res;
    }

    /**
     * Convert array to string
     * @param array $data
     * @param bool $prettyPrint
     * @return string
     */
    public function fromArrayToString(array $data,bool $prettyPrint = false) : string {
        if ($prettyPrint) {
            return json_encode($data,JSON_PRETTY_PRINT);
        } else {
            return json_encode($data);
        }
    }

}
