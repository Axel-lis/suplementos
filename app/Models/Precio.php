<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Producto;

class Precio extends Model
{
    protected $fillable = [
        'producto_id',
        'tipo', // 'publico', 'privado', 'agotado' 
        'valor',
        'fecha_vigencia'
    ];

    public function producto() {
        return $this->belongsTo(Producto::class);
    }
}