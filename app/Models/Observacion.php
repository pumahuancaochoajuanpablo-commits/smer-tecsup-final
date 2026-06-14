<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Observacion extends Model
{
    protected $table = 'observaciones';

    protected $fillable = ['entrevista_id', 'texto'];

    public function entrevista()
    {
        return $this->belongsTo(Entrevista::class);
    }
}
