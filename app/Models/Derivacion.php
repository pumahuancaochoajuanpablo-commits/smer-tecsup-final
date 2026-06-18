<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Derivacion extends Model
{
    protected $table = 'derivaciones';

    protected $fillable = [
        'estudiante_id',
        'tutor_id',
        'motivo',
        'descripcion',
        'estado',
        'responsable_bienestar',
        'observaciones',
        'fecha_derivacion',
        'fecha_respuesta',
    ];

    protected $casts = [
        'observaciones' => 'json',
        'fecha_derivacion' => 'datetime',
        'fecha_respuesta' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Estudiante::class);
    }

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(Tutor::class);
    }
}
