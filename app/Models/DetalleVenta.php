<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\Precio;

class DetalleVenta extends Model
{
    protected $fillable = [
        'venta_id',
        'producto_id',
        'precio_id',
        'cantidad',
        'subtotal'
    ];

    public function venta() {
        return $this->belongsTo(Venta::class);
    }

    public function producto() {
        return $this->belongsTo(Producto::class);
    }
    
    public function precio() {
    return $this->belongsTo(Precio::class);
}
}