<?php

namespace App\Services\PhoneVerification;

use App\Exceptions\PhoneCodeNotSent;
use App\Services\VerificationCodes;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

final class WhatsAppPhoneVerifier implements PhoneVerifier
{
    private const GRAPH_URL = 'https://graph.facebook.com';

    public function __construct(
        private readonly VerificationCodes $codes,
        private readonly string $token,
        private readonly string $phoneNumberId,
        private readonly string $template,
        private readonly string $language,
        private readonly string $graphVersion,
    ) {
    }

    public function send(string $phone): void
    {
        $code = $this->codes->issue("phone:{$phone}");

        try {
            Http::withToken($this->token)
                ->post(self::GRAPH_URL . "/{$this->graphVersion}/{$this->phoneNumberId}/messages", $this->message($phone, $code))
                ->throw();
        } catch (RequestException|ConnectionException $exception) {
            report($exception);

            throw PhoneCodeNotSent::make();
        }
    }

    public function check(string $phone, string $code): bool
    {
        return $this->codes->check("phone:{$phone}", $code);
    }

    private function message(string $phone, string $code): array
    {
        return [
            'messaging_product' => 'whatsapp',
            'to' => ltrim($phone, '+'),
            'type' => 'template',
            'template' => [
                'name' => $this->template,
                'language' => ['code' => $this->language],
                'components' => [
                    ['type' => 'body', 'parameters' => [['type' => 'text', 'text' => $code]]],
                    ['type' => 'button', 'sub_type' => 'url', 'index' => '0', 'parameters' => [['type' => 'text', 'text' => $code]]],
                ],
            ],
        ];
    }
}
