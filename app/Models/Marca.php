<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Producto;

class Marca extends Model
{
    protected $fillable = ['nombre', 'proveedor']; // Ejemplo de campos adicionales

    public function productos() {
        return $this->hasMany(Producto::class);
    }
}