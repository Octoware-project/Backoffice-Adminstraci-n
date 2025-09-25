<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConfiguracionHoras extends Model
{
    use HasFactory;

    protected $table = 'configuracion_horas';
    
    protected $fillable = [
        'valor_por_hora',
        'activo',
        'observaciones'
    ];

    protected $casts = [
        'valor_por_hora' => 'decimal:2',
        'activo' => 'boolean',
    ];

    /**
     * Obtener el valor por hora actualmente vigente
     */
    public static function getValorActual()
    {
        $config = self::where('activo', true)
                     ->latest('created_at')
                     ->first();
                     
        return $config ? $config->valor_por_hora : 0;
    }

    /**
     * Obtener la configuración activa actual
     */
    public static function getConfiguracionActual()
    {
        return self::where('activo', true)
                  ->latest('created_at')
                  ->first();
    }

    /**
     * Activar esta configuración y desactivar las demás
     */
    public function activar()
    {
        // Desactivar todas las configuraciones
        self::where('activo', true)->update(['activo' => false]);
        
        // Activar esta
        $this->update(['activo' => true]);
        
        return $this;
    }

    /**
     * Historial de configuraciones
     */
    public static function getHistorial($limit = 10)
    {
        return self::orderBy('created_at', 'desc')
                  ->limit($limit)
                  ->get();
    }
}