<?php

namespace App\Core\Helpers;

class ApiRequest {

    private string $url;

    public function __construct($url = false) {
        if ($url) {
            $this->url = $url;
        }
    }

    public function get($url = false, $data = false, $headers = array()) {
        $this->request('GET', $url, $data, $headers);
    }

    public function post($url = false, $data = false, $headers = array()) {
        $this->request('POST', $url, $data, $headers);
    }

    public function put($url = false, $data = false, $headers = array()) {
        $this->request('PUT', $url, $data, $headers);
    }

    public function delete($url = false, $data = false, $headers = array()) {
        $this->request('DELETE', $url, $data, $headers);
    }

    public function request($method, $url = false, $data = false, $headers = array()) {
        if ($url === false) {
            $url = $this->url;
        }
        $curl = curl_init($url);
        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                break;
            case "DELETE":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                break;
            default:
                if ($data) {
                    $url = sprintf("%s?%s", $url, http_build_query($data));
                }
        }
        // Optional Authentication:
        //curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        //curl_setopt($curl, CURLOPT_USERPWD, "username:password");
        if ($headers !== null) {
            if (empty($headers)) {
                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                ));
            } else {
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            }
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        // EXECUTE:
        $result = curl_exec($curl);
        if (!$result) {
            die("Connection Failure");
        }
        curl_close($curl);
        return $result;
    }

}
