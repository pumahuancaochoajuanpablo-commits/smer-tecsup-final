<?php

namespace App\Models;

use App\Services\BrevoPasswordResetMailer;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol_id',
        'estado',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'estado' => 'boolean',
        ];
    }

    public function rol()
    {
        return $this->belongsTo(Role::class, 'rol_id');
    }

    public function tutor()
    {
        return $this->hasOne(Tutor::class);
    }

    public function estudiante()
    {
        return $this->hasOne(Estudiante::class);
    }

    public function sendPasswordResetNotification($token): void
    {
        if (app()->environment('testing')) {
            $this->notify(new ResetPassword($token));
            return;
        }

        app(BrevoPasswordResetMailer::class)->send($this, $token);
    }
}
