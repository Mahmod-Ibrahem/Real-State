<?php

namespace App\Services\PhoneVerification;

interface PhoneVerifier
{
    public function send(string $phone): void;

    public function check(string $phone, string $code): bool;
}
