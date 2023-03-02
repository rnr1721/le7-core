<?php

namespace le7\Core\User;

use le7\Core\Database\Database;
use le7\Core\User\UserFind;
use le7\Core\Config\ConfigInterface;
use le7\Core\User\Notifications\NotificationsInterface;
use le7\Core\View\HtmlTemplate;
use le7\Core\User\Verification\VerificationCodeInterface;
use le7\Core\User\UserLoginInterface;
use \Exception;

class LoginForm {

    private $vcodeTemplate = 'email/verification_code';
    private array $errors = [];
    private Database $db;
    private UserFind $userFind;
    private ConfigInterface $config;
    private NotificationsInterface $notifications;
    private HtmlTemplate $htmlTemplate;
    private UserLoginInterface $userLogin;
    private VerificationCodeInterface $verificationCode;

    public function __construct(
            Database $db,
            ConfigInterface $config,
            UserLoginInterface $userLogin,
            VerificationCodeInterface $verificationCode,
            HtmlTemplate $htmlTemplate,
            NotificationsInterface $notifications,
            UserFind $userFind
    ) {
        $this->config = $config;
        $this->userLogin = $userLogin;
        $this->verificationCode = $verificationCode;
        $this->htmlTemplate = $htmlTemplate;
        $this->notifications = $notifications;
        $this->userFind = $userFind;
        $this->db = $db;
    }

    public function login(string|null $login, string|null $password, string|null $vcode = null): string|null {
        
        if (!$login || !$password) {
            $this->errors[] = _('Username or password empty');
            return null;
        }
        
        $pUser = $this->userFind->getUserByFields($this->db, $login);

        if (!$pUser) {
            $this->errors[] = _('Username or password not correct');
            return null;
        }

        if ($this->config->getUserLoginVerification()) {
            if (!$vcode) {
                $this->errors[] = _('Empty verification code');
                return null;
            }
            if ($this->verificationCode->verifyCode($pUser->id, $vcode)) {
                $this->verificationCode->deleteCode($pUser->id);
            } else {
                $this->errors[] = _('Incorrect verification code');
                return null;
            }
        }

        try {
            $result = $this->userLogin->login($pUser->export(), $password);
        } catch (Exception $ex) {
            unset($ex);
            $result = null;
        }
        $this->errors = array_merge($this->errors, $this->userLogin->getErrors());
        return $result;
    }

    public function getErrors() {
        return $this->errors;
    }

    public function logout() {
        return $this->userLogin->logout();
    }

    public function setVcode(string $username) {
        $user = $this->userFind->getUserByFields($this->db, $username);
        if ($user) {
            $vcode = $this->verificationCode->setCode($user->id);
            if ($vcode) {
                $this->htmlTemplate->setTemplate($this->vcodeTemplate);
                $this->htmlTemplate->assign('vcode', $vcode);
                $body = $this->htmlTemplate->compile();
                $this->notifications->sendMessage([
                    'to' => $user['email'],
                    'subject' => _('Verification code'),
                    'body' => $body
                ]);
                $this->errors = array_merge($this->errors, $this->notifications->getErrors());
                if (count($this->errors) === 0) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Add fields as array or comma-separated string
     * @param string|array $loginFields
     * @return self
     */
    public function setLoginField(string|array $loginFields): self {
        $this->userFind->setSearchField($loginFields);
        return $this;
    }

    public function setVerificationCodeTemplate($vCodeTemplate): self {
        $this->vcodeTemplate = $vCodeTemplate;
        return $this;
    }

}
