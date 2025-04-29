<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Producto;

class Balance extends Model
{
    protected $fillable = [
        'fecha',
        'tipo_periodo', // 'diario' o 'mensual'
        'producto_id',
        'total_operaciones',
        'comercio' // Asumo que es un campo de ubicación comercial
    ];

    public function producto() {
        return $this->belongsTo(Producto::class);
    }
}