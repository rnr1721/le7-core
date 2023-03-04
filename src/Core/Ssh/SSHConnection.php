<?php

namespace App\Core\Ssh;

class SSHConnection {

    private $connection;

    public function __construct(SSHConnectionInterface $connection) {
        $this->connection = $connection->getConnection();
    }

    public function copy(string $sourcePath, $destinationPath) {
        $sftp = ssh2_sftp($this->connection);
        $sftpStream = fopen('ssh2.sftp://' . $sftp . $destinationPath, 'w');

        try {
            $dataToSend = file_get_contents($sourcePath);

            fwrite($sftpStream, $dataToSend);

            fclose($sftpStream);
        } catch (\Exception $e) {
            echo $e;
            fclose($sftpStream);
        }

        //ssh2_scp_send($this->connection, $sourcePath, $destinationPath, $createMode);
    }

    public function unlink(string $sourcePath) {
        $sftp = ssh2_sftp($this->connection);
        ssh2_sftp_unlink($sftp, $sourcePath);
    }

}
