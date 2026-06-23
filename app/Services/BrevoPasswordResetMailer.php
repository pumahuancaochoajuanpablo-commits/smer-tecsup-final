<?php

namespace App\Services;

use App\Models\User;
class BrevoPasswordResetMailer
{
    public function __construct(private BrevoEmailService $emailService)
    {
    }

    public function send(User $user, string $token): void
    {
        $resetUrl = route('password.reset', [
            'token' => $token,
            'email' => $user->getEmailForPasswordReset(),
        ]);

        $this->emailService->send(
            $user->getEmailForPasswordReset(),
            $user->name,
            'Restablecer contrasena - SMER Tecsup',
            view('emails.password-reset', [
                    'user' => $user,
                    'resetUrl' => $resetUrl,
            ])->render(),
            "Hola {$user->name}. Para restablecer tu contrasena ingresa a este enlace: {$resetUrl}"
        );
    }
}
