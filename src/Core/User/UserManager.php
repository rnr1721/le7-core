<?php

declare(strict_types=1);

namespace App\Core\User;

use App\Core\Database\DbManager;
use App\Core\Config\TopologyFsInterface;
use App\Core\User\Notifications\NotificationsInterface;
use App\Core\User\UserFind;
use App\Core\View\HtmlTemplate;
use App\Core\Instances\RouteInterface;
use App\Core\User\Verification\VerificationCodeDb;
use App\Core\User\Verification\VerificationCodeInterface;
use App\Core\User\UserIdentity;
use App\Core\User\UserLogin;
use App\Core\User\UserCheck;
use App\Core\User\UserCheck\UserCheckApi;
use App\Core\User\UserCheck\UserCheckWebSession;
use App\Core\User\UserCheck\UserCheckWebCookies;
use App\Core\User\UserLogin\UserLoginApi;
use App\Core\User\UserLogin\UserLoginWebSession;
use App\Core\User\UserLogin\UserLoginWebCookies;
use App\Core\User\UserLoginInterface;
use App\Core\User\Tokens\TokensDb;
use App\Core\User\Tokens\TokensInterface;
use App\Core\User\Passwords\PasswordsDb;
use App\Core\User\Passwords\PasswordsInterface;
use App\Core\Request\Request;
use App\Core\Config\ConfigInterface;

class UserManager
{

    private DbManager $databaseFactory;
    private NotificationsInterface $notifications;
    private TopologyFsInterface $topologyFs;
    private ?PasswordsInterface $passwords = null;
    private ?TokensInterface $tokens = null;
    private ConfigInterface $config;
    private Request $request;

    public function __construct(DbManager $databaseFactory, ConfigInterface $config, Request $request, TopologyFsInterface $topologyFs, NotificationsInterface $notifications)
    {
        $this->config = $config;
        $this->request = $request;
        $this->topologyFs = $topologyFs;
        $this->notifications = $notifications;
        $this->databaseFactory = $databaseFactory;
    }

    private function getUserLoginWeb(): UserLoginInterface
    {
        if ($this->config->getUserIdentity() === 'cookies') {
            $loginProvider = new UserLoginWebCookies($this->config, $this->request, $this->getTokens(), $this->getPasswords());
        }
        if ($this->config->getUserIdentity() === 'session') {
            $loginProvider = new UserLoginWebSession($this->request, $this->getTokens(), $this->getPasswords());
        }
        return new UserLogin($loginProvider);
    }

    private function getUserLoginApi(): UserLoginInterface
    {
        $loginProvider = new UserLoginApi($this->request, $this->getTokens(), $this->getPasswords());
        return new UserLogin($loginProvider);
    }

    public function getUserCheckWeb(): UserCheckInterface
    {
        $this->getTokens();
        if ($this->config->getUserIdentity() === 'cookies') {
            $check = new UserCheckWebCookies($this->request, $this->getPasswords());
        }
        if ($this->config->getUserIdentity() === 'session') {
            $check = new UserCheckWebSession();
        }
        return new UserCheck($this->tokens, $check);
    }

    public function getUserCheckApi(): UserCheckInterface
    {
        $this->getTokens();
        $check = new UserCheckApi($this->request);
        return new UserCheck($this->tokens, $check);
    }

    public function getTokens(): TokensInterface
    {
        if ($this->tokens === null) {
            $this->tokens = new TokensDb();
        }
        return $this->tokens;
    }

    public function getVerificetionCode(): VerificationCodeInterface
    {
        return new VerificationCodeDb();
    }

    private function getPasswords(): PasswordsInterface
    {
        if ($this->passwords === null) {
            $this->passwords = new PasswordsDb();
        }
        return $this->passwords;
    }

    public function getUserWeb(): UserIdentityInterface
    {
        return new UserIdentity($this->getUserCheckWeb());
    }

    public function getUserApi(): UserIdentityInterface
    {
        return new UserIdentity($this->getUserCheckApi());
    }

    public function getLoginForm(RouteInterface $route)
    {
        if ($route->getType() === 'web') {
            $userLogin = $this->getUserLoginWeb();
        }
        if ($route->getType() === 'api') {
            $userLogin = $this->getUserLoginApi();
        }
        $userFind = new UserFind();
        $db = $this->databaseFactory->getDb();
        return new LoginForm($db,$this->config, $userLogin, $this->getVerificetionCode(), $this->getHtmlTemplate(), $this->notifications, $userFind);
    }

    private function getHtmlTemplate()
    {
        return new HtmlTemplate($this->topologyFs);
    }

}
