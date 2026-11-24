<?php

namespace App\Services\PhoneVerification;

use App\Exceptions\PhoneCodeNotSent;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;
use Twilio\Rest\Verify\V2\ServiceContext;

final class TwilioPhoneVerifier implements PhoneVerifier
{
    private const CHANNEL = 'sms';
    private const APPROVED = 'approved';

    public function __construct(private readonly Client $twilio, private readonly string $serviceSid)
    {
    }

    public function send(string $phone): void
    {
        try {
            $this->service()->verifications->create($phone, self::CHANNEL);
        } catch (TwilioException $exception) {
            report($exception);

            throw PhoneCodeNotSent::make();
        }
    }

    public function check(string $phone, string $code): bool
    {
        try {
            return $this->service()->verificationChecks->create(['to' => $phone, 'code' => $code])->status === self::APPROVED;
        } catch (TwilioException) {
            // Twilio answers 404 once the code has expired or was already used
            return false;
        }
    }

    private function service(): ServiceContext
    {
        return $this->twilio->verify->v2->services($this->serviceSid);
    }
}
