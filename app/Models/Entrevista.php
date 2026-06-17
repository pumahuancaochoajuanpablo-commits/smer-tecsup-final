<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrevista extends Model
{
    protected $table = 'entrevistas';

    protected $fillable = [
        'asignacion_id', 'fecha',
        'acad_2', 'emoc_2', 'soc_2', 'econ_2', 'fam_2', 'salud_2',
        'puntaje_total', 'nivel_riesgo', 'documento',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
        ];
    }

    public function asignacion()
    {
        return $this->belongsTo(Asignacion::class);
    }

    public function recomendacion()
    {
        return $this->belongsTo(Recomendacion::class, 'nivel_riesgo', 'nivel_riesgo');
    }

    public function observacion()
    {
        return $this->hasOne(Observacion::class);
    }
}
