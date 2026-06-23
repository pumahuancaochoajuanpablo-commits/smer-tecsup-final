<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class BrevoEmailService
{
    public function send(string $toEmail, string $toName, string $subject, string $htmlContent, string $textContent): void
    {
        $apiKey = config('services.brevo.key');

        if (! $apiKey) {
            throw new RuntimeException('Falta configurar BREVO_API_KEY para enviar correos por Brevo API.');
        }

        $response = Http::timeout(20)
            ->withHeaders([
                'accept' => 'application/json',
                'api-key' => $apiKey,
                'content-type' => 'application/json',
            ])
            ->post('https://api.brevo.com/v3/smtp/email', [
                'sender' => [
                    'name' => config('mail.from.name'),
                    'email' => config('mail.from.address'),
                ],
                'to' => [
                    [
                        'name' => $toName,
                        'email' => $toEmail,
                    ],
                ],
                'subject' => $subject,
                'htmlContent' => $htmlContent,
                'textContent' => $textContent,
            ]);

        if ($response->failed()) {
            throw new RuntimeException('Brevo API rechazo el envio: '.$response->status().' '.$response->body());
        }
    }
}
