<?php

namespace App\Services\EmailVerification;

interface EmailVerifier
{
    public function send(string $email): void;

    public function check(string $email, string $code): bool;
}
