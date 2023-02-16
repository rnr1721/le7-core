<?php

namespace Core\Security;

use Exception;
use JetBrains\PhpStorm\ArrayShape;

class Csrf
{

    /**
     * @throws Exception
     */
    public function onlyGenerateToken(int $length = 64) : string {
        return bin2hex(random_bytes($length));
    }

    /**
     * @throws Exception
     */
    #[ArrayShape(['key' => "string", 'value' => "string"])]
    public function toSession() : array {
        $tokenKey = $this->onlyGenerateToken(3);
        $tokenValue = $this->onlyGenerateToken(32);
        $_SESSION['csrf_'.$tokenKey] = $tokenValue;
        return array(
            'key' => $tokenKey,
            'value' => $tokenValue
        );
    }

    /**
     * @param array $post
     * @return bool
     */
    public function check(array $post) : bool {
        foreach ($post as $item => $value) {
            if (str_starts_with($item, 'csrf_')) {
                if (array_key_exists($item,$_SESSION)) {
                    if ($_SESSION[$item] === $value) {
                        unset($_SESSION[$item]);
                        return true;
                    }
                }
            }
        }
        return false;
    }

}
