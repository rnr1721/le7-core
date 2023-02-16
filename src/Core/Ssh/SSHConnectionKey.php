<?php

namespace le7\Core\Ssh;

class SSHConnectionKey implements SSHConnectionInterface {

    private $connection;
    private string $host;
    private int $port = 22;
    private string $userName;
    private string $secret;
    private string $secretKey;
    private string $publicKey;
    private string $hostKey = 'ssh-rsa';

    public function getConnection() {

        $connection = ssh2_connect($this->host, $this->port, array('hostkey' => $this->hostKey));

        if (ssh2_auth_pubkey_file($connection, $this->userName,
                        $this->publicKey,
                        $this->secretKey, $this->secret)) {
            echo "Public Key Authentication Successful\n";
        } else {
            die('Public Key Authentication Failed');
        }
        return $connection;
    }

    public function setHost(string $hostname): SSHConnectionKey {
        $this->host = $hostname;
        return $this;
    }

    public function setPort(int $port): SSHConnectionKey {
        $this->port = $port;
        return $this;
    }

    public function setUsername(string $userName): SSHConnectionKey {
        $this->userName = $userName;
        return $this;
    }

    public function setSecret(string $secret): SSHConnectionKey {
        $this->secret = $secret;
        return $this;
    }

    public function setSecretKey(string $secretKey): SSHConnectionKey {
        $this->secretKey = $secretKey;
        return $this;
    }

    public function setPublicKey(string $publicKey): SSHConnectionKey {
        $this->publicKey = $publicKey;
        return $this;
    }

    public function setHostKey(string $hostKey): SSHConnectionKey {
        $this->hostKey = $hostKey;
        return $this;
    }

}
