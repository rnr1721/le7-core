<?php

declare(strict_types=1);

namespace App\Core\User\Notifications;

interface NotificationEmailInterface extends NotificationInterface {

    public function setFromEmail(string $fromEmail): self;

    public function setFromName(string $fromName):self;

    public function setToEmail(string|array $toEmail): self;

    public function setUsername(string $username): self;

    public function setPassword(string $password): self;

    public function setHost(string $host): self;

    public function setPort(string $port): self;

    public function setSecure(string $isSecure): self;

    public function setIsHtml(bool $isHtml): self;

    public function setSubject(string $subject): self;
    
    public function setSubjectBody(string $subject, string $body): self;

    public function setCharset(string $charset): self;
    
    public function setAttachments(string|array $attachments):self;
    
    public function setDebug(int $debug):self;
}
