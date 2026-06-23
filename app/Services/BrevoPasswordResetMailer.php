<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class BrevoPasswordResetMailer
{
    public function send(User $user, string $token): void
    {
        $apiKey = config('services.brevo.key');

        if (! $apiKey) {
            throw new RuntimeException('Falta configurar BREVO_API_KEY para enviar correos por Brevo API.');
        }

        $resetUrl = route('password.reset', [
            'token' => $token,
            'email' => $user->getEmailForPasswordReset(),
        ]);

        $senderEmail = config('mail.from.address');
        $senderName = config('mail.from.name');

        $response = Http::timeout(20)
            ->withHeaders([
                'accept' => 'application/json',
                'api-key' => $apiKey,
                'content-type' => 'application/json',
            ])
            ->post('https://api.brevo.com/v3/smtp/email', [
                'sender' => [
                    'name' => $senderName,
                    'email' => $senderEmail,
                ],
                'to' => [
                    [
                        'name' => $user->name,
                        'email' => $user->getEmailForPasswordReset(),
                    ],
                ],
                'subject' => 'Restablecer contrasena - SMER Tecsup',
                'htmlContent' => view('emails.password-reset', [
                    'user' => $user,
                    'resetUrl' => $resetUrl,
                ])->render(),
                'textContent' => "Hola {$user->name}. Para restablecer tu contrasena ingresa a este enlace: {$resetUrl}",
            ]);

        if ($response->failed()) {
            throw new RuntimeException('Brevo API rechazo el envio: '.$response->status().' '.$response->body());
        }
    }
}
