<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

final class VerificationCodes
{
    public const TTL_SECONDS = 600;

    public function issue(string $key): string
    {
        $code = str_pad((string) random_int(0, 999_999), 6, '0', STR_PAD_LEFT);

        Cache::put($this->cacheKey($key), Hash::make($code), self::TTL_SECONDS);

        return $code;
    }

    public function check(string $key, string $code): bool
    {
        $hash = Cache::get($this->cacheKey($key));

        if ($hash === null || ! Hash::check($code, $hash)) {
            return false;
        }

        Cache::forget($this->cacheKey($key));

        return true;
    }

    private function cacheKey(string $key): string
    {
        return "verification-code:{$key}";
    }
}
