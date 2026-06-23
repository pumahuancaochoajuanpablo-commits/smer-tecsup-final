<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    protected $table = 'estudiantes';

    protected $fillable = ['user_id', 'codigo', 'carrera', 'ciclo', 'grupo', 'edad', 'estado'];

    protected function casts(): array
    {
        return [
            'estado' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class);
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class);
    }
}
