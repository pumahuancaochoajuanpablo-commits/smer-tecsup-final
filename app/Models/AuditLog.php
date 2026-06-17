<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'audit_logs';

    protected $fillable = [
        'user_id',
        'accion',
        'modelo',
        'modelo_id',
        'detalles',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'detalles' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Registrar acción de auditoría
     */
    public static function registrar($accion, $modelo, $modeloId = null, $detalles = null)
    {
        // Guard against missing request() or auth() contexts (console, jobs, tests)
        try {
            $ip = request()->ip();
            $userAgent = request()->userAgent();
        } catch (\Throwable $e) {
            $ip = null;
            $userAgent = null;
        }

        $userId = null;
        try {
            $userId = auth()->id();
        } catch (\Throwable $e) {
            $userId = null;
        }

        // Normalize detalles: ensure it's an array for JSON casting
        if ($detalles !== null && !is_array($detalles)) {
            if (is_string($detalles)) {
                $decoded = json_decode($detalles, true);
                $detalles = $decoded ?? ['info' => $detalles];
            } else {
                $detalles = ['info' => (string) $detalles];
            }
        }

        return self::create([
            'user_id' => $userId,
            'accion' => $accion,
            'modelo' => $modelo,
            'modelo_id' => $modeloId,
            'detalles' => $detalles,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
        ]);
    }
}
