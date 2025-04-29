<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Producto;

class Alerta extends Model
{
    protected $fillable = [
        'producto_id',
        'nivel_stock',
        'tipo_alerta' // 'stock_minimo' u otros
    ];

    public function producto() {
        return $this->belongsTo(Producto::class);
    }
}