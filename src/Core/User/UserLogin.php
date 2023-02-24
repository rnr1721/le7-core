<?php

declare(strict_types=1);

namespace le7\Core\User;

use le7\Core\User\UserLogin\UserLoginProvider;
use \Exception;

class UserLogin implements UserLoginInterface {

    private array $errors = [];
    private UserLoginProvider $userLoginProvider;

    public function __construct(UserLoginProvider $userLoginProvider) {
        $this->userLoginProvider = $userLoginProvider;
    }

    public function login(array|null $user, string|null $password, string $vcode = '') {
        if (empty($user)) {
            $this->errors[] = _('User not specified');
        }
        if (!$password || $password === '') {
            $this->errors[] = _('Password not specified');
        }
        if ($user['active'] === '0') {
            $message = _('User blocked');
            $this->errors[] = $message;
            throw new Exception($message);
        }
        if (count($this->errors) !== 0) {
            throw new Exception(_('Login error'));
        }
        $result = $this->userLoginProvider->login($user, $password, $vcode);
        $this->errors = array_merge($this->errors, $this->userLoginProvider->getErrors());
        return $result;
    }

    public function logout() {
        $this->userLoginProvider->logout();
    }

    public function getErrors(): array {
        return $this->errors;
    }

}
