<?php

namespace App\Services\EmailVerification;

use App\Exceptions\EmailCodeNotSent;
use App\Notifications\EmailVerificationCodeNotification;
use App\Services\VerificationCodes;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

final class MailEmailVerifier implements EmailVerifier
{
    public function __construct(private readonly VerificationCodes $codes)
    {
    }

    public function send(string $email): void
    {
        $code = $this->codes->issue("email:{$email}");

        try {
            Notification::route('mail', $email)->notify(new EmailVerificationCodeNotification($code));
        } catch (TransportExceptionInterface $exception) {
            report($exception);

            throw EmailCodeNotSent::make();
        }
    }

    public function check(string $email, string $code): bool
    {
        return $this->codes->check("email:{$email}", $code);
    }
}
