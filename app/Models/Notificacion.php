<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    protected $table = 'notificaciones';

    protected $fillable = ['estudiante_id', 'mensaje', 'leido'];

    protected function casts(): array
    {
        return [
            'leido' => 'boolean',
        ];
    }

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }
}
