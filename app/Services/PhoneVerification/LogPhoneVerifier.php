<?php

namespace App\Services\PhoneVerification;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

final class LogPhoneVerifier implements PhoneVerifier
{
    private const CODE_TTL_SECONDS = 600;

    public function send(string $phone): void
    {
        $code = str_pad((string) random_int(0, 999_999), 6, '0', STR_PAD_LEFT);

        Cache::put($this->key($phone), Hash::make($code), self::CODE_TTL_SECONDS);
        Log::info("Phone verification code for {$phone}: {$code}");
    }

    public function check(string $phone, string $code): bool
    {
        $hash = Cache::get($this->key($phone));

        if ($hash === null || ! Hash::check($code, $hash)) {
            return false;
        }

        Cache::forget($this->key($phone));

        return true;
    }

    private function key(string $phone): string
    {
        return "phone-verification:{$phone}";
    }
}
