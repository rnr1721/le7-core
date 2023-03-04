<?php

declare(strict_types=1);

namespace App\Core\User\Notifications;

use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\Exception;

class NotificationEmail implements NotificationEmailInterface {

    private PHPMailer $phpMailer;
    private array $attachments = [];
    private array $errors = [];
    private string $host;
    private string $port;
    private string $secure = 'ssl';
    private string $username;
    private string $password;
    private string $fromEmail;
    private string $fromName;
    private array $to = [];
    private int $debug = 0;
    private string $charset = 'UTF-8';
    private bool $isHtml;
    private string|null $subject = null;
    private string|null $body = null;

    public function __construct(PHPMailer $phpMailer) {
        $this->phpMailer = $phpMailer;
    }

    public function getErrors(): array {
        return $this->errors;
    }

    public function send(): bool {

        if ($this->subject === null) {
            $this->errors[] = _('Empty email subject');
        }

        if ($this->body === null) {
            $this->errors[] = _('Empty email body');
        }

        if (count($this->errors) !== 0) {
            throw new Exception("Erros while sending email");
        }

        $this->phpMailer->IsSMTP();
        $this->phpMailer->SMTPDebug = $this->debug;
        $this->phpMailer->SMTPAuth = true;
        $this->phpMailer->SMTPSecure = $this->secure;
        $this->phpMailer->Host = $this->host;
        $this->phpMailer->Port = $this->port;
        $this->phpMailer->Username = $this->username;
        $this->phpMailer->Password = $this->password;
        $this->phpMailer->IsHTML = $this->isHtml;
        $this->phpMailer->CharSet = $this->charset;
        $this->phpMailer->SetFrom($this->fromEmail, $this->fromName);
        if (!empty($this->attachments)) {
            foreach ($this->attachments as $attachment) {
                $this->phpMailer->addAttachment($attachment, basename($attachment));
            }
        }
        $this->phpMailer->Subject = $this->subject;
        $this->phpMailer->Body = $this->body;
        foreach ($this->to as $email) {
            $this->phpMailer->ClearAllRecipients();
            $this->phpMailer->AddAddress($email);
            try {
                if (!$this->phpMailer->Send()) {
                    $this->errors[] = _('Email message failed') . ': ' . $email . ': ' . $this->phpMailer->ErrorInfo;
                }
            } catch (Exception $e) {
                $this->errors[] = $e->getMessage() . ': ' . $this->phpMailer->ErrorInfo;
            }
        }
        if (count($this->errors) === 0) {
            return true;
        } else {
            throw new Exception(_('Email send failed'));
        }
        return false;
    }

    public function setCharset(string $charset): self {
        $this->charset = $charset;
        return $this;
    }

    public function setFromEmail(string $fromEmail): self {
        $this->fromEmail = $fromEmail;
        return $this;
    }

    public function setFromName(string $fromName): self {
        $this->fromName = $fromName;
        return $this;
    }

    public function setHost(string $host): self {
        $this->host = $host;
        return $this;
    }

    public function setIsHtml(bool $isHtml): self {
        $this->isHtml = $isHtml;
        return $this;
    }

    public function setOptions(array $options): self {
        if (!empty($options['host'])) {
            $this->host = $options['host'];
        }
        if (!empty($options['port'])) {
            $this->port = $options['port'];
        }
        if (!empty($options['secure'])) {
            $this->secure = $options['secure'];
        }
        if (!empty($options['username'])) {
            $this->username = $options['username'];
        }
        if (!empty($options['password'])) {
            $this->password = $options['password'];
        }
        if (!empty($options['from_email'])) {
            $this->fromEmail = $options['from_email'];
        }
        if (!empty($options['host'])) {
            $this->fromName = $options['from_name'];
        }
        if (!empty($options['to'])) {
            $this->setToEmail($options['to']);
        }
        if (!empty($options['debug'])) {
            $this->debug = $options['debug'];
        }
        if (!empty($options['charset'])) {
            $this->charset = $options['charset'];
        }
        if (!empty($options['is_html'])) {
            $this->isHtml = $options['is_html'];
        }
        if (!empty($options['subject'])) {
            $this->subject = $options['subject'];
        }
        if (!empty($options['body'])) {
            $this->body = $options['body'];
        }
        return $this;
    }

    public function setPassword(string $password): self {
        $this->password = $password;
        return $this;
    }

    public function setPort(string $port): self {
        $this->port = $port;
        return $this;
    }

    public function setSecure(string $secure): self {
        $this->secure = $secure;
        return $this;
    }

    public function setSubject(string $subject): self {
        $this->subject = $subject;
        return $this;
    }

    public function setBody(string $body): self {
        $this->body = $body;
        return $this;
    }

    public function setSubjectBody(string $subject, string $body): self {
        $this->subject = $subject;
        $this->body = $body;
        return $this;
    }

    public function setToEmail(string|array $toEmail): self {
        if (is_string($toEmail)) {
            if (!in_array($toEmail, $this->to)) {
                $this->to[] = $toEmail;
            }
        }
        if (is_array($toEmail)) {
            foreach ($toEmail as $email) {
                if (!in_array($email, $this->to)) {
                    $this->to[] = $email;
                }
            }
        }
        return $this;
    }

    public function setUsername(string $username): self {
        $this->username = $username;
        return $this;
    }

    public function setAttachments(string|array $attachments): self {
        if (is_array($attachments)) {
            foreach ($attachments as $attachment) {
                if (!in_array($attachment, $this->attachments)) {
                    $this->attachments[] = $attachment;
                }
            }
        }
        if (is_string($attachments)) {
            if (!in_array($attachments, $this->attachments)) {
                $this->attachments[] = $attachments;
            }
        }
        return $this;
    }

    public function setDebug(int $debug): self {
        $this->debug = $debug;
        return $this;
    }

}
