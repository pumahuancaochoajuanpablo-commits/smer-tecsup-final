<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParametroRiesgo extends Model
{
    protected $table = 'parametros_riesgo';

    protected $fillable = ['indicador', 'peso', 'umbral_bajo', 'umbral_medio', 'umbral_alto'];
}
