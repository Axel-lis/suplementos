<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DetalleVenta;
class Venta extends Model
{
    protected $fillable = [
        'fecha',
        'total',
        'detalle_id' //relacion venta con detalle
        
    ];

    public function detalles() {
        return $this->hasMany(DetalleVenta::class);
    }
}