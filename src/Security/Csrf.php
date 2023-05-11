<?php

declare(strict_types=1);

namespace Core\Security;

use Core\Interfaces\Request;
use Core\Interfaces\Session;
use function bin2hex,
             random_bytes;
use \Throwable;
use \RuntimeException;

class Csrf implements \Stringable
{

    private Request $request;
    private Session $session;
    private string $tokenKey;
    private string $tokenKeyOld;
    private string $tokenPrefix = 'csrf_';
    private string $tokenPrefixOld = 'csrf_old_';

    public function __construct(Session $session, Request $request)
    {
        $this->session = $session;
        $this->request = $request;
        $uri = md5((string) $request->getUri());
        $this->tokenKey = $this->tokenPrefix . $uri;
        $this->tokenKeyOld = $this->tokenPrefixOld . $uri;
    }

    /**
     * Generate CSRF token
     * @param int<1, max> $length Length
     * @return string
     */
    public function generateToken(int $length = 64): string
    {
        try {
            return bin2hex(random_bytes($length));
        } catch (Throwable $e) {
            throw new RuntimeException('Failed to generate CSRF token', 0, $e);
        }
    }

    public function __toString(): string
    {
        $result = $this->write();
        $key = $result['key'];
        $value = $result['value'];
        return '<input type="hidden" name="' . $key . '" value="' . $value . '">';
    }

    /**
     * Write csrf token to session
     * @return array
     */
    public function write(): array
    {
        $tokenValue = $this->generateToken(16);
        $this->session->set($this->tokenKey, $tokenValue);
        return array(
            'key' => $this->tokenKey,
            'value' => $tokenValue
        );
    }

    /**
     * Check csrf token
     * @return bool
     */
    public function check(): bool
    {

        $params = $this->request->getParams();
        $tokenValue = (isset($params[$this->tokenKey]) ? $params[$this->tokenKey] : null);
        if (is_string($tokenValue) && $this->session->has($this->tokenKey)) {
            if ($this->session->get($this->tokenKey) === $tokenValue) {
                $this->session->set($this->tokenKeyOld, $tokenValue);
                $this->session->delete($this->tokenKey);
                return true;
            }
        } elseif (is_string($tokenValue) && $this->session->has($this->tokenKeyOld)) {
            if ($this->session->get($this->tokenKeyOld) === $tokenValue) {
                return true;
            } else {
                $this->session->delete($this->tokenKeyOld);
            }
        }
        return false;
    }

}
